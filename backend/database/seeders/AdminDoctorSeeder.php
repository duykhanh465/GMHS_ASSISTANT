<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminDoctorSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmhs.test'],
            [
                'name' => 'GMHS Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'doctor@gmhs.test'],
            [
                'name' => 'GMHS Doctor',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
