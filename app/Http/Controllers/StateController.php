<?php
namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 25);

        $states = State::with('country')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString(); // ⭐ MUST

        return view('admin.state.index', compact('states'));
    }

    public function create()
    {
        $countries = Country::orderBy('name', 'asc')->get();
        return view('admin.state.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name'       => 'required|string|max:255|unique:states,name',
        ]);

        State::create([
            'country_id' => $request->country_id,
            'name'       => $request->name,
        ]);

        return redirect()->route('state.index')->with('success', 'State created successfully.');
    }

    public function edit($id)
    {
        $state     = State::findOrFail($id);
        $countries = Country::orderBy('name', 'asc')->get();

        return view('admin.state.edit', compact('state', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name'       => 'required|string|max:255|unique:states,name,' . $id,
        ]);

        $state = State::findOrFail($id);
        $state->update([
            'country_id' => $request->country_id,
            'name'       => $request->name,
        ]);

        return redirect()->route('state.index')->with('success', 'State updated successfully.');
    }

    public function destroy($id)
    {
        $state = State::findOrFail($id);
        $state->delete();

        return redirect()->route('state.index')->with('success', 'State deleted successfully.');
    }

    // ✅ Export CSV Method
    public function exportCSV()
    {
        $states = State::with('country')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=states_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $callback = function () use ($states) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, ['ID', 'State Name', 'Country', 'Created At']);

            // CSV Data
            foreach ($states as $state) {
                fputcsv($file, [
                    $state->id,
                    $state->name,
                    $state->country ? $state->country->name : 'N/A',
                    $state->created_at ? $state->created_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    // ✅ Export Excel Method
    public function exportExcel()
    {
        // Option 1: যদি Laravel Excel প্যাকেজ ইন্সটল করা থাকে
        // return Excel::download(new StatesExport, 'states.xlsx');

        // Option 2: Temporary - CSV রিটার্ন করুন
        return $this->exportCSV();

        // Option 3: কমিং সুন মেসেজ
        // return redirect()->route('state.index')->with('info', 'Excel export feature coming soon!');
    }

    // ✅ Export PDF Method
    public function exportPDF()
    {
        // Option 1: যদি DomPDF/Barryvdh প্যাকেজ ইন্সটল করা থাকে
        /*
        $states = State::with('country')->get();
        $pdf = \PDF::loadView('admin.state.export-pdf', compact('states'));
        return $pdf->download('states.pdf');
        */

        // Option 2: Temporary - CSV রিটার্ন করুন
        return $this->exportCSV();

        // Option 3: কমিং সুন মেসেজ
        // return redirect()->route('state.index')->with('info', 'PDF export feature coming soon!');
    }

    public function bulkDelete(Request $request)
    {
        // Get the IDs from request
        $ids = $request->input('ids');

        // Debug: Uncomment to see what's coming
        // dd($ids, $request->all());

        // If ids is null or empty, return error
        if (! $ids) {
            return redirect()->back()
                ->with('error', 'No states selected for deletion.');
        }

        // If ids is a string (comma separated), convert to array
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        // Ensure we have an array and sanitize
        $ids = array_map('intval', (array) $ids);
        $ids = array_filter($ids); // Remove empty values
        $ids = array_unique($ids); // Remove duplicates

        // If no valid IDs left, return error
        if (empty($ids)) {
            return redirect()->back()
                ->with('error', 'No valid states selected for deletion.');
        }

        // Delete the states
        State::whereIn('id', $ids)->delete();

        return redirect()->route('state.index')
            ->with('success', count($ids) . ' states deleted successfully.');
    }
}
