<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@joborder.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );
    }
}