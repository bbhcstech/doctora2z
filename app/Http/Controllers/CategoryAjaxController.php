<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CategoryAjaxController extends Controller
{
    /** Page */
    public function index()
    {
        // return your Blade view for the categories page
        // change the view path if yours is different
        return view('admin.category.inline');
    }

    /** Data list consumed by the table */
/** Data list (supports DataTables server-side + simple JSON) */
public function list(Request $request)
{
    // base query
    $base = Category::query()->select(['id','type','name','image']);

    // if DataTables request (has "draw"), do server-side processing
    if ($request->has('draw')) {
        $draw   = (int) $request->input('draw', 1);
        $start  = (int) $request->input('start', 0);          // offset
        $length = (int) $request->input('length', 10);        // page size
        $search = $request->input('search.value');            // global search
        $order  = $request->input('order', []);               // [{column:0, dir:"asc"}]
        $columns= $request->input('columns', []);             // columns meta from DT

        // total before filtering
        $recordsTotal = (clone $base)->count();

        // global search (type, name, id)
        if ($search !== null && $search !== '') {
            $term = trim($search);
            $base->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('type', 'like', "%{$term}%")
                  ->orWhere('id', (int) $term);
            });
        }

        // ordering
        $allowed = ['id','type','name','image'];
        if (is_array($order) && count($order)) {
            foreach ($order as $o) {
                $colIndex = (int) ($o['column'] ?? 0);
                $dir = strtolower($o['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
                $colName = $allowed[$colIndex] ?? 'id';
                // ignore non-sortable columns like checkbox/actions (map them properly in DT init)
                if (in_array($colName, $allowed, true)) {
                    $base->orderBy($colName, $dir);
                }
            }
        } else {
            $base->orderByDesc('id');
        }

        // filtered count
        $recordsFiltered = (clone $base)->count();

        // page slice
        if ($length > 0) {
            $base->skip($start)->take($length);
        }
        $rows = $base->get();

        // return DataTables format
        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $rows,
        ]);
    }

    // fallback: simple JSON with Laravel-style pagination meta (if not using DataTables)
    $length = (int) $request->input('length', 10);
    $page   = max(1, (int) $request->input('page', 1));

    $paginator = $base->orderByDesc('id')->paginate($length, ['*'], 'page', $page);

    return response()->json([
        'success' => true,
        'data'    => $paginator->items(),
        'meta'    => [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'from'         => $paginator->firstItem(),
            'to'           => $paginator->lastItem(),
        ],
    ]);
}


    /** Create */
    public function store(Request $request)
    {
        $request->validate([
            'type'  => ['required','string','max:255'],
            'name'  => [
                'required','string','max:255',
                Rule::unique('category','name')
                    ->where(fn($q)=>$q->where('type',$request->input('type')))
            ],
            'image' => ['nullable','image','max:2048'],
        ]);

        $filename = null;
        if ($request->hasFile('image')) {
            $filename = $this->storeUploadedImage($request->file('image'));
        }

        $cat = Category::create([
            'type'  => $request->string('type'),
            'name'  => $request->string('name'),
            'image' => $filename,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created',
            'data'    => $cat,
        ], 201);
    }

    /** Update single (your Blade calls POST /{id}) */
    public function update(Request $request, $id)
    {
        $cat = Category::findOrFail($id);

        $request->validate([
            'type'  => ['required','string','max:255'],
            'name'  => [
                'required','string','max:255',
                Rule::unique('category','name')
                    ->ignore($cat->id)
                    ->where(fn($q)=>$q->where('type',$request->input('type')))
            ],
            'image' => ['nullable','image','max:2048'],
        ]);

        $cat->type = $request->string('type');
        $cat->name = $request->string('name');

        if ($request->hasFile('image')) {
            // delete old file if exists
            $this->deleteImageIfExists($cat->image);
            $cat->image = $this->storeUploadedImage($request->file('image'));
        }

        $cat->save();

        return response()->json([
            'success' => true,
            'message' => 'Category updated',
            'data'    => $cat,
        ]);
    }

    /** Delete single */
    public function destroy($id)
    {
        $cat = Category::findOrFail($id);
        $this->deleteImageIfExists($cat->image);
        $cat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted',
        ]);
    }

    /** Bulk delete: expects category_ids[]=1&category_ids[]=2 ... */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('category_ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success'=>false,'message'=>'No ids provided'], 422);
        }

        $toDelete = Category::whereIn('id', $ids)->get(['id','image']);
        foreach ($toDelete as $row) {
            $this->deleteImageIfExists($row->image);
        }

        $count = Category::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "Deleted {$count} item(s)",
            'data'    => ['deleted' => $count],
        ]);
    }

    /**
     * Bulk update (optional — your Blade does per-row save,
     * but this endpoint supports batching if you want to use it later)
     * Payload:
     * items: [
     *   { id: 1, type: "clinic", name: "Cardiology" },
     *   ...
     * ]
     */
    public function bulkUpdate(Request $request)
    {
        $items = $request->input('items', []);
        if (!is_array($items) || empty($items)) {
            return response()->json(['success'=>false,'message'=>'No items'], 422);
        }

        $updated = 0; $failed = [];
        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $id   = Arr::get($item, 'id');
                $type = trim((string) Arr::get($item, 'type', ''));
                $name = trim((string) Arr::get($item, 'name', ''));

                if (!$id || $name === '' || $type === '') {
                    $failed[] = ['id'=>$id,'reason'=>'Missing fields'];
                    continue;
                }

                $exists = Category::where('id','!=',$id)
                    ->where('type',$type)
                    ->where('name',$name)
                    ->exists();
                if ($exists) {
                    $failed[] = ['id'=>$id,'reason'=>'Duplicate (type + name)'];
                    continue;
                }

                Category::whereKey($id)->update([
                    'type' => $type,
                    'name' => $name,
                ]);
                $updated++;
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success'=>false,
                'message'=>'Bulk update failed',
                'error'  => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success'=> true,
            'message'=> "Updated {$updated}, ".count($failed)." failed.",
            'data'   => ['updated'=>$updated,'failed'=>$failed],
        ]);
    }

    /** Separate image upload endpoint (if you want to use it from a different UI) */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => ['required','image','max:2048'],
        ]);

        $filename = $this->storeUploadedImage($request->file('image'));

        return response()->json([
            'success'  => true,
            'message'  => 'Image uploaded',
            'filename' => $filename,
            'url'      => asset('admin/uploads/category/'.$filename),
        ]);
    }

    /** Download sample CSV */
    public function downloadSample()
    {
        $csv = "type,name\nclinic,Pediatrics Clinic\ndoctor,Dermatology\n";
        return new StreamedResponse(function() use ($csv) {
            echo $csv;
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="categories_sample.csv"',
        ]);
    }

    /** Export CSV (simple and dependency-free) */
    public function exportCsv()
    {
        $rows = Category::orderBy('id')->get(['id','type','name','image']);

        return new StreamedResponse(function() use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id','type','name','image']);
            foreach ($rows as $r) {
                fputcsv($out, [$r->id, $r->type, $r->name, $r->image]);
            }
            fclose($out);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="categories.csv"',
        ]);
    }

    /** Export Excel (requires phpoffice/phpspreadsheet) */
    public function exportExcel()
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            return response()->json([
                'success'=>false,
                'message'=>'Install phpoffice/phpspreadsheet to export Excel: composer require phpoffice/phpspreadsheet'
            ], 422);
        }

        $rows = Category::orderBy('id')->get(['id','type','name','image']);

        $sheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ws = $sheet->getActiveSheet();
        $ws->fromArray(['id','type','name','image'], null, 'A1');
        $i = 2;
        foreach ($rows as $r) {
            $ws->setCellValue("A{$i}", $r->id);
            $ws->setCellValue("B{$i}", $r->type);
            $ws->setCellValue("C{$i}", $r->name);
            $ws->setCellValue("D{$i}", $r->image);
            $i++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, 'categories.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /** Export PDF (requires barryvdh/laravel-dompdf) */
    public function exportPdf()
    {
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class) && !class_exists(\Barryvdh\DomPDF\PDF::class)) {
            return response()->json([
                'success'=>false,
                'message'=>'Install barryvdh/laravel-dompdf to export PDF: composer require barryvdh/laravel-dompdf'
            ], 422);
        }

        $rows = Category::orderBy('id')->get();
        $html = view('admin.categories.pdf', ['rows'=>$rows])->render();

        /** @var \Barryvdh\DomPDF\PDF|\Barryvdh\DomPDF\Facade\Pdf $pdf */
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('categories.pdf');
    }

    /** Import CSV/XLSX using PhpSpreadsheet (CSV works without it too) */
    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => ['required','file','mimes:csv,txt,xls,xlsx'],
        ]);

        $file = $request->file('excel_file');
        $ext  = strtolower($file->getClientOriginalExtension());

        $rows = [];
        if ($ext === 'csv' || $ext === 'txt') {
            // Basic CSV reader (no dependencies)
            $handle = fopen($file->getRealPath(), 'r');
            if ($handle === false) {
                return response()->json(['success'=>false,'message'=>'Unable to read file'], 422);
            }
            $header = null;
            while (($data = fgetcsv($handle)) !== false) {
                if ($header === null) { $header = $data; continue; }
                $rows[] = array_combine($header, $data);
            }
            fclose($handle);
        } else {
            // XLS/XLSX — need PhpSpreadsheet
            if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
                return response()->json([
                    'success'=>false,
                    'message'=>'Install phpoffice/phpspreadsheet to import Excel: composer require phpoffice/phpspreadsheet'
                ], 422);
            }
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getRealPath());
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $header = [];
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                $cells = [];
                foreach ($row->getCellIterator() as $cell) {
                    $cells[] = trim((string)$cell->getValue());
                }
                if ($rowIndex === 1) { $header = $cells; continue; }
                if (count(array_filter($cells)) === 0) continue; // skip empty rows
                $rows[] = array_combine($header, $cells);
            }
        }

        $created = 0; $updated = 0; $skipped = [];
        foreach ($rows as $i => $r) {
            $type = trim((string) Arr::get($r, 'type', ''));
            $name = trim((string) Arr::get($r, 'name', ''));
            if ($type === '' || $name === '') {
                $skipped[] = ['row'=>$i+2,'reason'=>'Missing type/name'];
                continue;
            }

            $existing = Category::where('type',$type)->where('name',$name)->first();
            if ($existing) {
                $updated++;
                continue; // already exists; keep as-is (or update if you prefer)
            }

            Category::create(['type'=>$type,'name'=>$name]);
            $created++;
        }

        return response()->json([
            'success' => true,
            'message' => "Import complete. Created {$created}, existing {$updated}, skipped ".count($skipped).".",
            'data'    => ['created'=>$created,'existing'=>$updated,'skipped'=>$skipped],
        ]);
    }

    /** Helpers */

    /** Store image to public/admin/uploads/category and return basename */
    protected function storeUploadedImage(\Illuminate\Http\UploadedFile $file): string
    {
        $dir = public_path('admin/uploads/category');
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $ext = strtolower($file->getClientOriginalExtension());
        $safeName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = preg_replace('/[^a-zA-Z0-9\-_]+/', '-', $safeName);
        $filename = $safeName.'-'.time().'-'.mt_rand(1000,9999).'.'.$ext;

        // move to public path
        $file->move($dir, $filename);

        return $filename;
    }

    protected function deleteImageIfExists(?string $filename): void
    {
        if (!$filename) return;
        $path = public_path('admin/uploads/category/'.$filename);
        if (is_file($path)) @unlink($path);
    }
}
