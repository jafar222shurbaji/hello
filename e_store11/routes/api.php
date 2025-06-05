<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeContoller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Routs For employee

Route::apiResource('/users', EmployeeController::class)->middleware(['optional_auth']);
Route::post('/users/{id}/restore', [EmployeeController::class, "restore"]);
Route::get('/deletedusers', [EmployeeController::class, "DeletedUsers"]);


// Routs for users

Route::post('register', [AuthController::class, 'register']);
Route::post('check', [AuthController::class, 'checkCode']);//vervited_at
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route for user product


// Show and update profile for user

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('myprofile', [ProfileController::class, 'show']);
    Route::put('updateprofile', [ProfileController::class, 'update']);
    Route::put('changePassword', [ProfileController::class, 'changePass']);
    Route::delete('deleteAccount', [ProfileController::class, 'destroy']);
});


// Home APIs

Route::post('createProduct', [HomeContoller::class, 'store']);
