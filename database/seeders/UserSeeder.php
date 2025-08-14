<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin',
            'role' => 'admin',
            'password' => 'admin',
        ]);
        
        User::create([
            'name' => 'customer',
            'email' => 'customer@customer',
            'role' => 'customer',
            'password' => 'customer',
        ]);

        User::create([
            'name' => 'cashier',
            'email' => 'cashier@cashier',
            'role' => 'cashier',
            'password' => 'cashier',
        ]);


        User::factory(3)->admin()->create(['password' => 'password']);
        User::factory(3)->customer()->create(['password' => 'password']);
        User::factory(3)->cashier()->create(['password' => 'password']);

    }
}
