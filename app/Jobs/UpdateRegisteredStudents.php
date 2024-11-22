<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Redis;
use InosoftUniversity\SharedModels\Course;
use InosoftUniversity\SharedModels\Department;
use InosoftUniversity\SharedModels\Registration;

class UpdateRegisteredStudents implements ShouldQueue
{
    use Queueable;

    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): bool
    {
        // calculate new number of registered students from registrations.course_id
        $courseRegisteredStudents = Registration::where('course_id', $this->data['course_id'])->count();

        // calculate new number of registered students in departments
        $courseIds = Course::where('department_id', $this->data['department_id'])->pluck('id');
        $departmentRegisteredStudents = Registration::whereIn('course_id', $courseIds)->distinct('user_id')->count();

        // update department
        $department = Department::find($this->data['department_id']);
        $department->registered_students = $departmentRegisteredStudents;
        $department->save();
        
        // update course
        $course = Course::find($this->data['course_id']);
        $course->registered_students = $courseRegisteredStudents;
        $course->save();
        
        // update redis
        Redis::set('department_list', json_encode(Department::with('courses')->get()));

        return true;
    }
}
