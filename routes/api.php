<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function() {
    // register student/instructor
    Route::post('register', [UserController::class, 'register']);

    // auth
    Route::get('logout', [AuthController::class, 'logout']);

    // users
    Route::get('user', [UserController::class, 'getUserInfo']);
    Route::get('all-students', [UserController::class, 'getAllStudents']);
    Route::get('all-instructors', [UserController::class, 'getAllInstructors']);

    // courses
    Route::post('courses/create', [CourseController::class, 'create']);
    Route::get('courses/view-all', [CourseUserController::class, 'viewAll']);
    Route::get('courses/view-registered', [CourseUserController::class, 'viewRegisteredCourses']);
    Route::post('courses/register', [CourseUserController::class, 'registerSelf']);
    Route::post('register-student', [CourseUserController::class, 'registerStudent']); 
    
});

