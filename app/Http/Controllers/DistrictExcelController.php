<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DistrictsImport;
use App\Exports\DistrictsSampleExport;

class DistrictExcelController extends Controller
{
    // GET /import-districts (form)
    public function showImportForm()
    {
        return view('admin.district.import');
    }

    // POST /import-districts (do import)
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:51200', // up to 50 MB
        ]);

        @ini_set('max_execution_time', '0');
        @ini_set('memory_limit', '1024M');

        $import = new DistrictsImport();
        Excel::import($import, $request->file('file'));

        return back()
            ->with('success', 'Import completed')
            ->with('import_summary', $import->summary());
    }

    // GET /import-districts/sample (download template)
    public function downloadSample()
    {
        return Excel::download(new DistrictsSampleExport, 'districts_sample.xlsx');
    }
}
