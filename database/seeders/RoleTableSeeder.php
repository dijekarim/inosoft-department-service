<?php

namespace Database\Seeders;

use InosoftUniversity\SharedModels\Permission;
use InosoftUniversity\SharedModels\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'ADMIN'],
            ['name' => 'STUDENT'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $adminRole = Role::where('name', 'ADMIN')->first();
        $studentRole = Role::where('name', 'STUDENT')->first();

        $adminRole->permissions()->attach(Permission::all());
        $studentRole->permissions()->attach(Permission::whereIn(
            'name', [
                'view_courses', 
                'list_courses', 
                'register_course', 
                'view_students', 
                'view_departments', 
                'list_departments'
            ])->get());
    }
}
