<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CancelReason;
use App\Models\Indent;

class CancelReasonsTableSeeder extends Seeder
{
    public function run()
    {
        // Create some cancel reasons
        $cancelReasons = [
            ['reason' => 'Not Responding'],
            ['reason' => 'Material not ready'],
            ['reason' => 'Duplicate Enquiry'],
            ['reason' => 'Unavailability of vehicle'],
            ['reason' => 'Trip Postponed'],
            // Add more reasons as needed
        ];

        // Insert cancel reasons into the database
        foreach ($cancelReasons as $reason) {
            CancelReason::create($reason);
        }

        // Retrieve some Indent records (you may adjust this query based on your data)
        $indents = Indent::take(5)->get();

        // Associate each indent with some cancel reasons
        foreach ($indents as $indent) {
            // Attach random cancel reasons to each indent
            $indent->cancelReasons()->attach(
                CancelReason::inRandomOrder()->take(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
