<?php
namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('id', 'desc')->paginate(25);
        return view('admin.country.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.country.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
        ]);

        Country::create(['name' => $request->name]);

        return redirect()->route('country.index')->with('success', 'Country created successfully.');
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.country.edit', compact('country'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $id,
        ]);

        $country = Country::findOrFail($id);
        $country->update(['name' => $request->name]);

        return redirect()->route('country.index')->with('success', 'Country updated successfully.');
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();
        return redirect()->route('country.index')->with('success', 'Country deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $countryIds = $request->ids;

        if (! $countryIds) {
            return back()->with('error', 'No countries selected!');
        }

        Country::whereIn('id', $countryIds)->delete();
        return back()->with('success', 'Selected countries deleted successfully!');
    }

    // ✅ CSV Export Method
    public function exportCSV()
    {
        $countries = Country::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=countries_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $callback = function () use ($countries) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, ['ID', 'Country Name', 'Created At', 'Updated At']);

            // CSV Data
            foreach ($countries as $country) {
                fputcsv($file, [
                    $country->id,
                    $country->name,
                    $country->created_at ? $country->created_at->format('Y-m-d H:i:s') : 'N/A',
                    $country->updated_at ? $country->updated_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    // ✅ Excel Export Method
    public function exportExcel()
    {
        // Option 1: যদি Laravel Excel প্যাকেজ থাকে
        // return Excel::download(new CountriesExport, 'countries.xlsx');

        // Option 2: Temporary - CSV রিটার্ন করুন (একই কাজ করবে)
        return $this->exportCSV();

        // Option 3: কমিং সুন মেসেজ
        // return redirect()->route('country.index')->with('info', 'Excel export feature coming soon!');
    }

    // ✅ PDF Export Method
    public function exportPDF()
    {
        // Option 1: যদি DomPDF/Barryvdh প্যাকেজ থাকে
        /*
        $countries = Country::all();
        $pdf = \PDF::loadView('admin.country.export-pdf', compact('countries'));
        return $pdf->download('countries.pdf');
        */

        // Option 2: Temporary - CSV রিটার্ন করুন (একই কাজ করবে)
        return $this->exportCSV();

        // Option 3: কমিং সুন মেসেজ
        // return redirect()->route('country.index')->with('info', 'PDF export feature coming soon!');
    }
}
