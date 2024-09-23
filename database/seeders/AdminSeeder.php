<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@gmail.com',
                'contact' => '9629414890',
                'designation' => 'superadmin',
                'remarks' => 'No Remarks',
                'status' => 1,
                'role_id' => 1,
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'Sales',
                'email' => 'sales1@gmail.com',
                'contact' => '9629414890',
                'designation' => 'sales',
                'remarks' => 'No Remarks',
                'status' => 1,
                'role_id' =>3,
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'Sales2',
                'email' => 'sales2@gmail.com',
                'contact' => '9629414890',
                'designation' => 'sales',
                'remarks' => 'No Remarks',
                'status' => 1,
                'role_id' => 3,
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'Supplier1',
                'email' => 'supplier1@gmail.com',
                'contact' => '9629414890',
                'designation' => 'supplier',
                'remarks' => 'No Remarks',
                'status' => 1,
                'role_id' => 4,
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'Supplier2',
                'email' => 'supplier2@gmail.com',
                'contact' => '9629414890',
                'designation' => 'supplier',
                'remarks' => 'No Remarks',
                'status' => 1,
                'role_id' => 4,
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'Accounts',
                'email' => 'account@gmail.com',
                'contact' => '9629414890',
                'designation' => 'account',
                'remarks' => 'No Remarks',
                'status' => 1,
                'role_id' => 5,
                'password' => bcrypt('12345678'),
            ],
        ];

        foreach ($users as $key => $user) {
            User::create($user);
        }

    }
}

