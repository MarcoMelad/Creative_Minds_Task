<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create faq user with users
        User::factory(5)->create();

        // create Custom user To use it
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@creative.com',
            'password' => bcrypt('password@123'),
            'phone_number' => '1234567891',
            'user_name' => 'User 1',
            'address' => 'Address 1',
            'type' => 'user',
        ]);

        // create Admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@creative.com',
            'password' => bcrypt('password@123'),
            'phone_number' => '1234567890',
            'user_name' => 'admin',
            'is_admin' => true
        ]);
    }
}
