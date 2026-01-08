<?php
namespace App\Http\Controllers;

use App\Imports\CountryImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CountryExcelController extends Controller
{
    public function showUploadForm()
    {
        return view('admin.country.upload');
    }

    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);
        // dd($request->file('file')); // Debugging file input
        Excel::import(new CountryImport, $request->file('file'));

        return redirect()->back()->with('success', 'Countries imported successfully.');
    }
}
