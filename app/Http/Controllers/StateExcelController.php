<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StatesImport;

class StateExcelController extends Controller
{
    public function showImportForm()
    {
        return view('admin.state.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new StatesImport, $request->file('file'));

        return back()->with('success', 'States imported successfully!');
    }
}
