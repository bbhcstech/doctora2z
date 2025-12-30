<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CategoryImport;

class CategoryExcelController extends Controller
{
    public function showImportForm()
    {
        return view('admin.category.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new CategoryImport, $request->file('file'));

        return back()->with('success', 'Category imported successfully!');
    }
}
