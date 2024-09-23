<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed roles with values starting from 0
        $roles = [
            ['type' => 'superadmin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['type' => 'admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['type' => 'sales', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['type' => 'supply', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['type' => 'accounts', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        // Insert the data into the roles table
        Role::insert($roles);
    }
}
