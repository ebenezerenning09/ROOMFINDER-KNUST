<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');

        if (blank($adminEmail) || blank($adminPassword)) {
            throw new \RuntimeException('ADMIN_EMAIL and ADMIN_PASSWORD must be set in .env to seed the admin user.');
        }

        User::query()->updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Alfa Ebenazer',
                'password' => Hash::make($adminPassword),
                'is_admin' => true,
            ]
        );
    }
}
