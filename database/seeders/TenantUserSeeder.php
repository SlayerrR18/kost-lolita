<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Penghuni Kost Lolita 1',
                'password' => Hash::make('user123'),
                'role' => 'tenant',
            ]
        );
    }
}

