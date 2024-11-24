<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('/departments', [DepartmentController::class, 'index'])->name('department.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('department.store');
    Route::get('/departments/{id}', [DepartmentController::class, 'show'])->name('department.show');
    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('department.update');
    Route::post('/register', [DepartmentController::class, 'register'])->name('department.register');
});