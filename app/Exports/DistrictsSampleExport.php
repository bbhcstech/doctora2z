<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DistrictsSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['country_name', 'state_name', 'district_name', 'city_name', 'pincode'];
    }

    public function array(): array
    {
        return [
            ['India', 'West Bengal',   'Hooghly',     'Singur',       '712409'],
            ['India', 'West Bengal',   'Bardhaman',   'Kalna',        '713101'],
            ['India', 'Maharashtra',   'Mumbai',      'Mumbai',       '400001'],
            ['India', 'Odisha',        'Cuttack',     'Cuttack',      '753001'],
            ['India', 'Tamil Nadu',    'Chennai',     'T. Nagar',     '600017'],
            ['India', 'West Bengal',   'Purulia',     '',             ''],
            ['India', 'Karnataka',     'Bengaluru',   'Koramangala',  '560034'],
        ];
    }
}
