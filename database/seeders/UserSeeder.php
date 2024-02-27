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
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mailinator.com',
            'is_customer' => false,
        ]);

        User::factory()->create([
            'name' => 'Cliente 1',
            'email' => 'customer1@mailinator.com',
            'is_customer' => true,
        ]);

        User::factory()->create([
            'name' => 'Cliente 2',
            'email' => 'customer2@mailinator.com',
            'is_customer' => true,
        ]);
    }
}
