<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use InosoftUniversity\SharedModels\Department;
use Carbon\Carbon;

class DepartmentsTableSeeder extends Seeder
{
    public function run()
    {
        $start_date = Carbon::create(2024, 12, 1);
        $end_date = Carbon::create(2024, 12, 31);
        $dept_names = ['Computer Science', 'Electrical Engineering', 'Mechanical Engineering'];

        foreach ($dept_names as $name) {
            Department::create([
                'name' => $name,
                'start_reg' => $start_date,
                'end_reg' => $end_date
            ]);
        }
    }
}
