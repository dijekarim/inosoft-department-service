<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use InosoftUniversity\SharedModels\Course;
use InosoftUniversity\SharedModels\Department;
use InosoftUniversity\SharedModels\Role;
use InosoftUniversity\SharedModels\User;

use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

beforeEach(function () {
    Artisan::call('migrate:fresh --seed');
});

it('Failed cause of registration date', function () {
    // Arrange: Find a user, department, and course
    $user = User::where('role_id', Role::where('name', 'STUDENT')->first()->id)->first();
    $department = Department::first();
    $course = Course::where('department_id', $department->id)->first();

    // Act: Attempt to log in
    $loginResponse = Http::post('http://user-service/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    // Act: Simulate registration request
    $response = postJson('/api/register', [
        'course_id' => $course->id,
    ], [
        'Authorization' => 'Bearer ' . $loginResponse->json('access_token'),
    ]);

    // Assert: Check if the registration was successful
    $response->assertStatus(422)
             ->assertJson(['error' => 'Registration is not open for department Computer Science']);
});

it('Successful registration', function () {
    // Arrange: Find a user, department, and course
    $user = User::where('role_id', Role::where('name', 'STUDENT')->first()->id)->first();
    $department = Department::first();
    $course = Course::where('department_id', $department->id)->first();

    // Arrange registration date
    $department->start_reg = now();
    $department->end_reg = now()->addDays(1);
    $department->save();

    // Act: Attempt to log in
    $loginResponse = Http::post('http://user-service/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    // Act: Simulate registration request
    $response = postJson('/api/register', [
        'course_id' => $course->id,
    ], [
        'Authorization' => 'Bearer ' . $loginResponse->json('access_token'),
    ]);

    // Assert: Check if the registration was successful
    $response->assertStatus(201)
             ->assertJson(['message' => 'Registration successful.']);
});
