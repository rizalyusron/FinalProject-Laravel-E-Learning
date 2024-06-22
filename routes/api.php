<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Auth\GoogleSocialiteController;
use App\Http\Controllers\LessonController;

/**
 * route "/register"
 * @method "POST"
 */
Route::post('/register', App\Http\Controllers\api\RegisterController::class)->name('register');

// Route::get('/course', App\Http\Controllers\api\RegisterController::class)->name('register');
/**
 * route "/login"
 * @method "POST"
 */
Route::post('/login', App\Http\Controllers\api\LoginController::class)->name('login');
/**
 * route "/user"
 * @method "GET"
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('courses', [CourseController::class, 'index']);
    Route::post('courses', [CourseController::class, 'store']);
    Route::get('courses/{id}', [CourseController::class, 'show']);
    Route::put('courses/{id}', [CourseController::class, 'update']);
    Route::delete('courses/{id}', [CourseController::class, 'destroy']);


    Route::get('attachment', [AttachmentController::class, 'index']);
    Route::post('attachment', [AttachmentController::class, 'store']);
    Route::get('attachment/{id}', [AttachmentController::class, 'show']);
    Route::get('attachment/course/{id}', [AttachmentController::class, 'showbycourseid']);
    Route::put('attachment/{id}', [AttachmentController::class, 'update']);
    Route::delete('attachment/{id}', [AttachmentController::class, 'destroy']);

    Route::get('lessons', [LessonController::class, 'index']);
    Route::post('lessons', [LessonController::class, 'store']);
    Route::get('lessons/{id}', [LessonController::class, 'show']);
    Route::get('lessons/course/{id}', [LessonController::class, 'showbycourseid']);
    Route::put('lessons/{id}', [LessonController::class, 'update']);
    Route::delete('lessons/{id}', [LessonController::class, 'destroy']);
});

Route::get('attachment/downloadcourse/{id}', [AttachmentController::class, 'download']);
/**
 * route "/logout"
 * @method "POST"
 */
Route::post('/logout', App\Http\Controllers\api\LogoutController::class)->name('logout');
