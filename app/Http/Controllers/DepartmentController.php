<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendRegistrationNotificationJob;
use App\Jobs\UpdateRegisteredStudents;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use InosoftUniversity\SharedModels\Course;
use InosoftUniversity\SharedModels\Department;
use InosoftUniversity\SharedModels\Registration;
use InosoftUniversity\SharedModels\User;

class DepartmentController extends Controller
{
    public function register(Request $request)
    {
        // Get the authenticated user
        $authenticatedUser = Auth::user();
        if (!$authenticatedUser) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        // Check if the user is a student
        if ($authenticatedUser->role === 'student') {
            $userId = $authenticatedUser->id;
        } else if ($authenticatedUser->role === 'admin') {
            // Validate user_id only if the role is not 'student'
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);
            $userId = $request->user_id;
        } else {
            return response()->json(['error' => 'You are not allowed to register.'], 401);
        }
    
        $user = User::find($userId);
        $course = Course::find($request->course_id);
    
        // Get the department associated with the course
        $department = $course->department;

        // Check if the current date is within the open registration period
        $currentDate = Carbon::now();
        $registrationStartDate = $department->start_reg;
        $registrationEndDate = $department->end_reg;  

        if ($currentDate->lt($registrationStartDate) || $currentDate->gt($registrationEndDate)) {
            return response()->json(['error' => 'Registration is not open for department ' . $department->name], 422);
        }

        // Check if the department quota is full
        $registrationsCount = Registration::whereIn(
            'course_id', 
            $department->courses->pluck('id')
        )->count();

        if ($registrationsCount >= $department->quota) {
            return response()->json(['error' => 'The department has reached its quota.'], 422);
        }
    
        // Check grade requirements
        if ($user->math_grade < $department->min_math_grade || $user->science_grade < $department->min_science_grade) {
            return response()->json(['error' => 'Your grades do not meet the requirements for this course.'], 422);
        }
        
        // TODO: add db transaction on error registration
        try {
            DB::beginTransaction(); // Start a transaction
            
            // Proceed with registration
            Registration::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'registration_date' => now(),
            ]);

            // Update department and course list on redis
            UpdateRegisteredStudents::dispatch([
                'department_id' => $department->id,
                'course_id' => $course->id,
            ]);

            // Send registration notification via RabbitMQ
            SendRegistrationNotificationJob::dispatch([
                'user_id' => $user->id,
                'name' => $user->name,
                'course' => $course->name,
            ]);

            DB::commit();
            return response()->json(['message' => 'Registration successful.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) { // error duplicate entry
                return response()->json(['error' => 'Registration failed. You already have registered to this course.'], 500);
            }
            return response()->json(['error' => 'Registration failed.'], 500);
        }
    }

    public function index(Request $request) {
        // Get the authenticated user
        $authenticatedUser = Auth::user();
        if (!$authenticatedUser) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }
        
        $departments = Redis::get('department_lists');

        if (!$departments) {
            $departments = Department::with('courses')->get();
            Redis::set('department_lists', json_encode($departments));
            return response()->json(['data' => $departments]);
        } else {
            return response()->json(['data' => json_decode($departments)]);
        }

    }
}
