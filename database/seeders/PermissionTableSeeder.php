<?php

namespace Database\Seeders;

use InosoftUniversity\SharedModels\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view_courses',
            'list_courses',
            'create_courses',
            'edit_courses',
            'delete_courses',
            'view_students',
            'list_students',
            'create_students',
            'edit_students',
            'delete_students',
            'view_departments',
            'list_departments',
            'create_departments',
            'edit_departments',
            'delete_departments',
            'register_course',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
