<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use InosoftUniversity\SharedModels\Course;
use InosoftUniversity\SharedModels\Department;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        // Find departments
        $csDepartment = Department::where('name', 'Computer Science')->first();
        $eeDepartment = Department::where('name', 'Electrical Engineering')->first();
        $meDepartment = Department::where('name', 'Mechanical Engineering')->first();

        // Create courses for each department
        Course::create([
            'name' => 'Intro to Programming',
            'department_id' => $csDepartment->id,
            'quota' => 30,
            'credits' => 3,
        ]);

        Course::create([
            'name' => 'Algorithms and Data Structures',
            'department_id' => $csDepartment->id,
            'quota' => 30,
            'credits' => 4,
        ]);

        Course::create([
            'name' => 'Circuit Theory',
            'department_id' => $eeDepartment->id,
            'quota' => 30,
            'credits' => 3,
        ]);

        Course::create([
            'name' => 'Mechanical Vibrations',
            'department_id' => $meDepartment->id,
            'quota' => 30,
            'credits' => 3,
        ]);
    }
}
