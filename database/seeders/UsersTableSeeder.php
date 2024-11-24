<?php

namespace Database\Seeders;

use InosoftUniversity\SharedModels\Role;
use Illuminate\Database\Seeder;
use InosoftUniversity\SharedModels\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create a student user
        User::create([
            'name' => 'Student One',
            'email' => 'student1@example.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'STUDENT')->first()->id,
            'math_grade' => 8.5,
            'science_grade' => 8.2,
        ]);

        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'ADMIN')->first()->id,
            'math_grade' => 0, // Admin doesn't need grades
            'science_grade' => 0,
        ]);
    }
}
