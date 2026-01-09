<?php
namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $countries = Country::when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
        })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.country.index', compact('countries', 'search'));
    }
    

    public function create()
    {
        return view('admin.country.create');
    }

    public function store(Request $request)
    {
        // ✅ শুধু name validation
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
        ]);

        // ✅ শুধু name save করুন
        Country::create([
            'name' => $request->name,
        ]);

        return redirect()->route('country.index')->with('success', 'Country created successfully.');
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.country.edit', compact('country'));
    }

    public function update(Request $request, $id)
    {
        // ✅ শুধু name validation
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $id,
        ]);

        $country = Country::findOrFail($id);

        // ✅ শুধু name update করুন
        $country->update([
            'name' => $request->name,
        ]);

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
        // Debug: Log the request data
        Log::info('Bulk Delete Request Data:', $request->all());

        // Get the IDs from request
        $countryIds = $request->ids;

        // If no IDs provided
        if (! $countryIds) {
            return back()->with('error', 'No countries selected for deletion!');
        }

        // If IDs come as string (comma-separated), convert to array
        if (is_string($countryIds)) {
            $countryIds = explode(',', $countryIds);
        }

        // Ensure we have an array
        if (! is_array($countryIds)) {
            return back()->with('error', 'Invalid data format for deletion!');
        }

        // Convert all values to integers and remove empty values
        $countryIds = array_map('intval', $countryIds);
        $countryIds = array_filter($countryIds); // Remove 0 values
        $countryIds = array_unique($countryIds); // Remove duplicates

        // If no valid IDs after processing
        if (empty($countryIds)) {
            return back()->with('error', 'No valid countries selected for deletion!');
        }

        // Log the processed IDs
        Log::info('Processed IDs for deletion:', $countryIds);

        // Delete countries
        try {
            $deletedCount = Country::whereIn('id', $countryIds)->delete();

            Log::info('Deleted count:', ['count' => $deletedCount]);

            return back()->with('success', $deletedCount . ' countries deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Bulk delete error: ' . $e->getMessage());
            return back()->with('error', 'Error occurred while deleting countries: ' . $e->getMessage());
        }
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

            // CSV Header - শুধু name এবং timestamps
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
        return $this->exportCSV();
    }

    // ✅ PDF Export Method
    public function exportPDF()
    {
        return $this->exportCSV();
    }
}
