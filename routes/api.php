<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\CourseRegistration\CourseController;
use App\Http\Controllers\Api\v1\CourseRegistration\CourseStudentController;
use App\Http\Controllers\Api\v1\CourseRegistration\RegistrationController;
use App\Http\Controllers\Api\v1\RolePermission\PermissionController;
use App\Http\Controllers\Api\v1\RolePermission\RoleController;
use App\Http\Controllers\Api\v1\RolePermission\RolePermissionController;
use App\Http\Controllers\Api\v1\User\UserController;
use Illuminate\Support\Facades\Route;

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
    /**
     * Register and login user
     */
    Route::post('login', [AuthController::class, 'login']);
    Route::post('self-register-student', [AuthController::class, 'selfRegisterAsStudent']);

    Route::group(['middleware' => 'auth:api'], function () {
        /**
         * Authentication
         */
        Route::get('logout', [AuthController::class, 'logout']);

        /**
         * Roles
         */
        Route::apiResource('roles', RoleController::class);

        /**
         * Permission
         */
        Route::post('permissions/selected-groups-roles', [RolePermissionController::class, 'getPermissionsByGroupsRoles']);
        Route::post('permissions/update-role', [RolePermissionController::class, 'updateRolePermissions']);
        Route::post('permissions/assign-to-role', [RolePermissionController::class, 'assignPermissionsToRole']);
        Route::post('permissions/assign-to-user', [RolePermissionController::class, 'assignPermissionsToUser']);
        Route::post('permissions/revoke-from-role', [RolePermissionController::class, 'revokePermissionFromRole']);
        Route::post('permissions/revoke-from-user', [RolePermissionController::class, 'revokePermissionFromUser']);
        Route::get('permissions/by-group', [PermissionController::class, 'getPermissionsByGroups']);
        Route::apiResource('permissions', PermissionController::class)
            ->only(['index', 'show', 'update']);

        /**
         * Users
         */
        Route::get('my-info', [UserController::class, 'getLoggedInUserInfo']);
        Route::get('all-students', [UserController::class, 'getAllStudents']);
        Route::get('all-instructors', [UserController::class, 'getAllInstructors']);
        Route::apiResource('users', UserController::class);

        /**
         * Course registration
         */
        Route::get('courses/view-student-registered', [RegistrationController::class, 'viewStudentRegisteredCourses']);
        Route::get('courses/view-instructor-registered', [RegistrationController::class, 'viewInstructorRegisteredCourses']);
        Route::post('courses/self-register', [RegistrationController::class, 'selfRegisterCourses']);
        Route::post('courses/register-student', [RegistrationController::class, 'registerStudentsCourses']);

        /**
         * Courses
         */
        Route::get('courses/with-students', [CourseStudentController::class, 'index']);
        Route::apiResource('courses', CourseController::class);

    });
});
