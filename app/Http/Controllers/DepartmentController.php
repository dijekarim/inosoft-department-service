<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendRegistrationNotificationJob;

class DepartmentController extends Controller
{
    public function register(Request $request)
    {
        // TODO: Registration logic...
        dump('Job dispatched');

        // Dispatch the job to RabbitMQ
        SendRegistrationNotificationJob::dispatch([
            'user_id' => 1,
            'name' => 'Student 1',
            'course' => 'Science',
        ]);


        return response()->json(['message' => 'Registration successful'], 201);
    }
}
