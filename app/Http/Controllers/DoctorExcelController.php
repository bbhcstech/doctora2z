<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DoctorsImport;
use Illuminate\Support\Facades\Log;

class DoctorExcelController extends Controller
{
    public function showImportForm()
    {
        return view('admin.doctor_lists.import');
    }

               public function import(Request $request)
            {
                $request->validate([
                    'file' => 'required|mimes:xlsx,csv',
                ]);
            
                Log::info('File received: ' . $request->file('file')->getClientOriginalName());
            
                Excel::import(new DoctorsImport, $request->file('file'));
            
                return back()->with('success', 'Doctors imported successfully!');
            }
}
