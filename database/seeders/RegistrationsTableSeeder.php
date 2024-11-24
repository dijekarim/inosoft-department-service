<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use InosoftUniversity\SharedModels\Registration;
use InosoftUniversity\SharedModels\User;
use InosoftUniversity\SharedModels\Course;
use InosoftUniversity\SharedModels\Role;

class RegistrationsTableSeeder extends Seeder
{
    public function run()
    {
        // Get sample users and courses
        $student = User::where('role_id', Role::where('name', 'Student')->first()->id)->first();
        $csIntroToProgramming = Course::where('name', 'Intro to Programming')->first();

        // Create a registration
        Registration::create([
            'user_id' => $student->id,
            'department_id' => $csIntroToProgramming->department_id,
            'course_id' => $csIntroToProgramming->id,
            'registration_date' => now(),
        ]);

        // Update registered students count
        $department = $csIntroToProgramming->department;
        $department->total_students_registered += 1;
        $department->save();

        // Update registered students count
        $csIntroToProgramming->total_students_registered += 1;
        $csIntroToProgramming->save();
    }
}
