<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\TruckType;

class TruckTypeSeeder extends Seeder
{
    public function run()
    {
        $truckTypes = [
            'TATA ACE',
            'ASHOK LEYLAND DOST',
            'MAHINDRA BOLERO PICK UP',
            'ASHOK LEYLAND BADA DOST',
            'TATA 407',
            'EICHER 14 FEET',
            'EICHER 17 FEET',
            'EICHER 19 FEET',
            'TATA 22 FEET',
            'TATA TRUCK (6 TYRE)',
            'TAURUS 16 T (10 TYRE)',
            'TAURUS 21 T (12 TYRE)',
            'TAURUS 25 T (14 TYRE)',
            'CONTAINER 20 FT',
            'CONTAINER 32 FT SXL',
            'CONTAINER 32 FT MXL',
            'CONTAINER 32 FT SXL / MXL HQ',
            '20 FEET OPEN ALL SIDE (ODC)',
            '28-32 FEET OPEN-TRAILOR JCB ODC',
            '32 FEET OPEN-TRAILOR ODC',
            '40 FEET OPEN-TRAILOR ODC',
            // Add more truck types as needed
        ];

        foreach ($truckTypes as $type) {
            TruckType::create(['name' => $type]);
        }
    }
}
