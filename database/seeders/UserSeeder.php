<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'TechNova Admin',
            'email' => 'admin@technova.com',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        $admin->profile()->create([
            'first_name' => 'TechNova',
            'last_name' => 'Admin',
            'bio' => 'The administrator of TechNova Newsroom, responsible for overseeing all operations and managing user accounts.',
            'department' => 'Management',
        ]);

        $writer = User::create([
            'name' => 'Ahmad Writer',
            'email' => 'writer@technova.com',
            'password' => Hash::make('Password123!'),
            'role' => 'writer',
            'is_active' => true,
        ]);
        $writer->profile()->create([
            'first_name' => 'Ahmad',
            'last_name' => 'Writer',
            'bio' => 'A software journalist and internal communications specialist for the Backend team.',
            'department' => 'Media & Content',
        ]);

        $reader = User::create([
            'name' => 'Samer Reader',
            'email' => 'reader@technova.com',
            'password' => Hash::make('Password123!'),
            'role' => 'reader',
            'is_active' => true,
        ]);
        $reader->profile()->create([
            'first_name' => 'Samer',
            'last_name' => 'Reader',
            'bio' => 'A backend software engineer for the main development team in 2026.',
            'department' => 'Engineering',
        ]);
    }
}
