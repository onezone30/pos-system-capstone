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
            'name' => 'Cesar Pinero',
            'email' => 'cpinero522003@gmail.com',
            'role' => 'admin',
            'password' => 'admin'
        ]);
        
        User::create([
            'name' => 'owner',
            'email' => 'owner@owner',
            'role' => 'owner',
            'password' => 'owner',
        ]);

        User::create([
            'name' => 'cashier',
            'email' => 'cashier@cashier',
            'role' => 'cashier',
            'password' => 'cashier',
        ]);


        User::factory(3)->admin()->create(['password' => 'password']);
        User::factory(3)->cashier()->create(['password' => 'password']);
        User::factory(3)->owner()->create(['password' => 'password']);

    }
}
