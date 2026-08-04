<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove old superadmin credentials if they exist
        \App\Models\User::where('role', 'superadmin')
            ->where('email', '!=', 'apro@mcc.edu.in')
            ->delete();

        \App\Models\User::updateOrCreate(
            ['email' => 'apro@mcc.edu.in'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('MCC@1837'),
                'role' => 'superadmin'
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'admin@mccigh.com'],
            [
                'name' => 'Admin User',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin'
            ]
        );
    }
}
