<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\District;
use App\Models\State;
use App\Models\Category;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class DoctorImportExportController extends Controller
{
    /**
     * Download sample CSV
     */
    public function downloadSample()
    {
        $headers = [
            'ID', 'Name', 'Email', 'Phone', 'Alt Phone', 'Speciality', 'Reg No', 'Council', 
            'Pincode', 'Website', 'Whatsapp', 'Facebook', 'Instagram', 'Address',
            'Country', 'State', 'District', 'City', 'Category', 'Clinic', 'Status',
            'Consultation Mode', 'Experience Years', 'Languages', 'Date of Birth'
        ];

        $sampleRow = [
            '', 'Dr. John Doe', 'john.doe@example.com', '9999999999', '8888888888',
            'Cardiology', 'REG-12345', 'Medical Council', '700001', 'https://example.com',
            '9999999999', 'https://facebook.com/johndoe', 'https://instagram.com/johndoe',
            '123 Main Street, Kolkata', 'India', 'West Bengal', 'Kolkata', 'Kolkata',
            'Cardiologist', 'City Clinic', 'active', 'face-to-face', '10', 'English,Hindi,Bengali',
            '1980-01-01'
        ];

        return new StreamedResponse(function () use ($headers, $sampleRow) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fputcsv($handle, $sampleRow);
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="doctors_sample.csv"',
        ]);
    }

    /**
     * Export CSV
     */
    public function exportCsv()
    {
        $columns = [
            'ID', 'Name', 'Email', 'Phone', 'Secondary Phone', 'Speciality', 'Degree',
            'Experience Years', 'Languages', 'Clinic ID', 'Clinic Name', 'Clinic Days',
            'Start Time', 'End Time', 'Registration No', 'Council', 'Pincode', 'Website',
            'WhatsApp', 'Facebook', 'Instagram', 'Address', 'Country', 'State', 'District',
            'City', 'Category', 'Status', 'Consultation Mode', 'Created At'
        ];

        $filename = 'doctors_' . date('Y-m-d') . '.csv';

        $callback = function() use ($columns) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);

            $query = Doctor::with(['country','state','district','city','category','clinic','pincode'])
                          ->orderBy('id', 'desc');

            foreach ($query->cursor() as $doctor) {
                fputcsv($out, [
                    $doctor->id,
                    $doctor->name,
                    $doctor->email,
                    $doctor->phone_number,
                    $doctor->phone_number_2,
                    $doctor->speciality,
                    $doctor->degree,
                    $doctor->experience_years,
                    $doctor->languages,
                    $doctor->clinic_id,
                    $doctor->clinic_name,
                    is_array($doctor->clinic_days) ? implode(',', $doctor->clinic_days) : $doctor->clinic_days,
                    $doctor->clinic_start_time,
                    $doctor->clinic_end_time,
                    $doctor->registration_no,
                    $doctor->council,
                    $doctor->pincode,
                    $doctor->website,
                    $doctor->whatsapp,
                    $doctor->facebook,
                    $doctor->instagram,
                    $doctor->address,
                    $doctor->country->name ?? '',
                    $doctor->state->name ?? '',
                    $doctor->district->name ?? '',
                    $doctor->city->name ?? '',
                    $doctor->category->name ?? '',
                    $doctor->status,
                    $doctor->consultation_mode,
                    optional($doctor->created_at)->toDateTimeString(),
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * Export Excel
     */
    public function exportExcel()
    {
        if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\IOFactory')) {
            return $this->exportCsv();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'ID', 'Name', 'Email', 'Phone', 'Secondary Phone', 'Speciality', 'Degree',
            'Experience Years', 'Languages', 'Clinic ID', 'Clinic Name', 'Clinic Days',
            'Start Time', 'End Time', 'Registration No', 'Council', 'Pincode', 'Website',
            'WhatsApp', 'Facebook', 'Instagram', 'Address', 'Country', 'State', 'District',
            'City', 'Category', 'Status', 'Consultation Mode', 'Created At'
        ];

        $sheet->fromArray($headers, null, 'A1');

        $doctors = Doctor::with(['country','state','district','city','category','clinic','pincode'])
                        ->orderBy('id', 'desc')
                        ->get();

        $rowNum = 2;
        foreach ($doctors as $doctor) {
            $sheet->fromArray([
                $doctor->id,
                $doctor->name,
                $doctor->email,
                $doctor->phone_number,
                $doctor->phone_number_2,
                $doctor->speciality,
                $doctor->degree,
                $doctor->experience_years,
                $doctor->languages,
                $doctor->clinic_id,
                $doctor->clinic_name,
                is_array($doctor->clinic_days) ? implode(',', $doctor->clinic_days) : $doctor->clinic_days,
                $doctor->clinic_start_time,
                $doctor->clinic_end_time,
                $doctor->registration_no,
                $doctor->council,
                $doctor->pincode,
                $doctor->website,
                $doctor->whatsapp,
                $doctor->facebook,
                $doctor->instagram,
                $doctor->address,
                $doctor->country->name ?? '',
                $doctor->state->name ?? '',
                $doctor->district->name ?? '',
                $doctor->city->name ?? '',
                $doctor->category->name ?? '',
                $doctor->status,
                $doctor->consultation_mode,
                optional($doctor->created_at)->toDateTimeString(),
            ], null, 'A'. $rowNum);
            $rowNum++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'doctors_'.date('Y-m-d').'.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }

    /**
     * Import CSV/Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'mimes:csv,txt,xls,xlsx', 'max:10240']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $failed = [];

        DB::beginTransaction();
        try {
            if (in_array($extension, ['csv', 'txt'])) {
                $this->importCsv($file, $created, $updated, $skipped, $failed);
            } else {
                $this->importExcel($file, $created, $updated, $skipped, $failed);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Import complete. Created: {$created}, Updated: {$updated}, Skipped: {$skipped}",
                'failed' => $failed
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function importCsv($file, &$created, &$updated, &$skipped, &$failed)
    {
        $handle = fopen($file->getRealPath(), 'r');
        $headers = fgetcsv($handle);
        $headers = array_map('strtolower', array_map('trim', $headers));

        $rowNum = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            
            if (count($row) !== count($headers)) {
                $failed[] = ['row' => $rowNum, 'error' => 'Column count mismatch'];
                continue;
            }

            $data = array_combine($headers, $row);
            $result = $this->importRow($data);
            
            if ($result === 'created') $created++;
            elseif ($result === 'updated') $updated++;
            else $skipped++;
        }

        fclose($handle);
    }

    private function importExcel($file, &$created, &$updated, &$skipped, &$failed)
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $headers = array_map('strtolower', array_map('trim', $rows[0]));
        unset($rows[0]);

        $rowNum = 2;
        foreach ($rows as $row) {
            if (count($row) !== count($headers)) {
                $failed[] = ['row' => $rowNum, 'error' => 'Column count mismatch'];
                $rowNum++;
                continue;
            }

            $data = array_combine($headers, $row);
            $result = $this->importRow($data);
            
            if ($result === 'created') $created++;
            elseif ($result === 'updated') $updated++;
            else $skipped++;
            
            $rowNum++;
        }
    }

    private function importRow($data)
    {
        // Normalize data
        $data = array_map('trim', $data);
        
        // Skip if no name
        if (empty($data['name'])) {
            return 'skipped';
        }

        // Prepare payload
        $payload = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone_number' => $data['phone'] ?? $data['phone_number'] ?? null,
            'phone_number_2' => $data['alt phone'] ?? $data['phone_number_2'] ?? null,
            'speciality' => $data['speciality'] ?? null,
            'degree' => $data['degree'] ?? null,
            'registration_no' => $data['reg no'] ?? $data['registration_no'] ?? null,
            'council' => $data['council'] ?? null,
            'website' => $data['website'] ?? null,
            'whatsapp' => $data['whatsapp'] ?? null,
            'facebook' => $data['facebook'] ?? null,
            'instagram' => $data['instagram'] ?? null,
            'address' => $data['address'] ?? null,
            'experience_years' => $data['experience years'] ?? $data['experience_years'] ?? null,
            'languages' => $data['languages'] ?? null,
            'status' => in_array(strtolower($data['status'] ?? ''), ['active', 'inactive']) 
                        ? strtolower($data['status']) 
                        : 'active',
            'consultation_mode' => in_array(strtolower($data['consultation mode'] ?? ''), 
                                            ['online', 'face-to-face', 'both', 'offline'])
                                   ? strtolower($data['consultation mode'])
                                   : 'face-to-face',
        ];

        // Handle location data
        if (!empty($data['country'])) {
            $country = Country::where('name', 'like', '%' . $data['country'] . '%')->first();
            if ($country) $payload['country_id'] = $country->id;
        }

        if (!empty($data['state'])) {
            $state = State::where('name', 'like', '%' . $data['state'] . '%')->first();
            if ($state) $payload['state_id'] = $state->id;
        }

        if (!empty($data['city'])) {
            $city = City::where('name', 'like', '%' . $data['city'] . '%')->first();
            if ($city) $payload['city_id'] = $city->id;
        }

        if (!empty($data['category'])) {
            $category = Category::where('name', 'like', '%' . $data['category'] . '%')->first();
            if ($category) $payload['category_id'] = $category->id;
        }

        if (!empty($data['clinic'])) {
            $clinic = Client::where('name', 'like', '%' . $data['clinic'] . '%')->first();
            if ($clinic) $payload['clinic_id'] = $clinic->id;
        }

        // Handle pincode
        if (!empty($data['pincode'])) {
            $pincodeRecord = Pincode::where('pincode', $data['pincode'])->first();
            if ($pincodeRecord) {
                $payload['pincode_id'] = $pincodeRecord->id;
                $payload['is_pincode_unknown'] = false;
                $payload['manual_pincode'] = null;
            } else {
                $payload['pincode_id'] = null;
                $payload['is_pincode_unknown'] = true;
                $payload['manual_pincode'] = $data['pincode'];
            }
        }

        // Check if doctor exists (by email or phone)
        $doctor = null;
        if (!empty($payload['email'])) {
            $doctor = Doctor::where('email', $payload['email'])->first();
        }
        
        if (!$doctor && !empty($payload['phone_number'])) {
            $doctor = Doctor::where('phone_number', $payload['phone_number'])->first();
        }

        if ($doctor) {
            // Update existing
            $doctor->update($payload);
            return 'updated';
        } else {
            // Create new
            Doctor::create($payload);
            return 'created';
        }
    }

    /**
     * Export PDF (placeholder - you can implement with DomPDF)
     */
    public function exportPdf()
    {
        // This is a placeholder. You can implement PDF export using DomPDF or similar
        return response()->json([
            'success' => false,
            'message' => 'PDF export not implemented yet'
        ], 501);
    }
}