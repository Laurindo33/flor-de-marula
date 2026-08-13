<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@flordemarula.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('FlorMarula#Admin2026'),
                'role' => 'Super Admin',
            ]
        );
    }
}
