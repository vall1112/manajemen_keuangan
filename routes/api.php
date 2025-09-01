<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Route
Route::middleware(['auth', 'json'])->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth');
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::prefix('setting')->group(function () {
    Route::get('', [SettingController::class, 'index']);
});

Route::middleware(['auth', 'verified', 'json'])->group(function () {
    Route::prefix('setting')->middleware('can:setting')->group(function () {
        Route::post('', [SettingController::class, 'update']);
    });

    Route::prefix('master')->group(function () {
        Route::middleware('can:master-user')->group(function () {
            Route::get('users', [UserController::class, 'get']);
            Route::post('users', [UserController::class, 'index']);
            Route::post('users/store', [UserController::class, 'store']);
            Route::apiResource('users', UserController::class)
                ->except(['index', 'store'])->scoped(['user' => 'uuid']);
        });

        Route::middleware('can:master-role')->group(function () {
            Route::get('roles', [RoleController::class, 'get'])->withoutMiddleware('can:master-role');
            Route::post('roles', [RoleController::class, 'index']);
            Route::post('roles/store', [RoleController::class, 'store']);
            Route::apiResource('roles', RoleController::class)
                ->except(['index', 'store']);
        });

        Route::middleware('can:master-teacher')->group(function () {
            Route::get('teachers', [TeacherController::class, 'get']);
            Route::post('teachers', [TeacherController::class, 'index']);
            Route::post('teachers/store', [TeacherController::class, 'store']);
            Route::apiResource('teachers', TeacherController::class)
                ->except(['index', 'store']);
        });

        Route::middleware('can:master-classroom')->group(function () {
            Route::get('classrooms', [ClassroomController::class, 'get']);
            Route::post('classrooms', [ClassroomController::class, 'index']);
            Route::post('classrooms/store', [ClassroomController::class, 'store']);
            Route::apiResource('classrooms', ClassroomController::class)
                ->except(['index', 'store']);
        });

        Route::middleware('can:master-student')->group(function () {
            Route::get('students', [StudentController::class, 'get']);
            Route::post('students', [StudentController::class, 'index']);
            Route::post('students/store', [StudentController::class, 'store']);
            Route::apiResource('students', StudentController::class)
                ->except(['index', 'store']);
        });

        Route::middleware('can:master-payment')->group(function () {
            Route::get('payment-types', [PaymentTypeController::class, 'get']);
            Route::post('payment-types', [PaymentTypeController::class, 'index']);
            Route::post('payment-types/store', [PaymentTypeController::class, 'store']);
            Route::apiResource('payment-types', PaymentTypeController::class)
                ->except(['index', 'store']);
        });

        Route::middleware('can:master-school-year')->group(function () {
            Route::get('school-years', [SchoolYearController::class, 'get']);
            Route::post('school-years', [SchoolYearController::class, 'index']);
            Route::post('school-years/store', [SchoolYearController::class, 'store']);
            Route::apiResource('school-years', SchoolYearController::class)
                ->except(['index', 'store']);
        });
        Route::middleware('can:master-pembayaran')->group(function () {
            Route::get('pembayaran', [PembayaranController::class, 'get']);
            Route::post('pembayaran', [PembayaranController::class, 'index']);
            Route::post('pembayaran', [PembayaranController::class, 'store']);
            Route::apiResource('pembayaran', PembayaranController::class)
                ->except(['pembayaran']);
        });
    });
});
