<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\User\UserController;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\CourseRegistration\CourseController;
use App\Http\Controllers\Api\v1\CourseRegistration\RegistrationController;

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
Route::prefix('v1')->group(function () {
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
        Route::get('courses/view-all', [CourseController::class, 'viewAll']);
        
        // course registration
        Route::get('courses/view-student-registered', [RegistrationController::class, 'viewStudentRegisteredCourses']);
        Route::get('courses/view-instructor-registered', [RegistrationController::class, 'viewInstructorRegisteredCourses']);
        Route::post('courses/self-register', [RegistrationController::class, 'selfRegisterCourses']);
        Route::post('courses/register-student', [RegistrationController::class, 'registerStudentsCourses']); 
        
    });
});
