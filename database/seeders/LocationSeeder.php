<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [ 'Ariyalur', 'Chengalpattu', 'Chennai', 'Coimbatore', 'Cuddalore', 'Dharmapuri', 'Dindigul',
            'Erode', 'Kallakurichi', 'Kancheepuram', 'Karur', 'Krishnagiri', 'Madurai', 'Mayiladuthurai',
            'Nagapattinam', 'Kanniyakumari', 'Namakkal', 'Perambalur', 'Pudukottai', 'Ramanathapuram',
            'Ranipet', 'Salem', 'Sivagangai', 'Tenkasi', 'Thanjavur', 'Theni', 'Thiruvallur', 'Thiruvarur',
            'Thoothukudi', 'Trichirappalli', 'Tirunelveli', 'Tirupathur', 'Tiruppur', 'Tiruvannamalai',
            'The Nilgiris', 'Vellore', 'Viluppuram', 'Virudhunagar'
        ];

        foreach ($locations as $location) {
            Location::create(['district' => $location]);
        }
    }
}