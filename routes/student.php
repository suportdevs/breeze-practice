<?php

use App\Http\Controllers\Student\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ConfirmablePasswordController;
use App\Http\Controllers\Admin\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\EmailVerificationPromptController;
use App\Http\Controllers\Student\NewPasswordController;
use App\Http\Controllers\Student\PasswordResetLinkController;
use App\Http\Controllers\Student\RegisteredUserController;
use App\Http\Controllers\Admin\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/student/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest-student')
                ->name('student.register');

Route::post('/student/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest-student')
                ->name('student.register');

Route::get('/student/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest-student')
                ->name('student.login');

Route::post('/student/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest-student')
                ->name('student.login');

Route::get('/student/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest-student:student')
                ->name('student.password.request');

Route::post('/student/forgot-password', [PasswordResetLinkController::class, 'submitForgetPasswordForm'])
                ->middleware('guest-student:student')
                ->name('student.password.email');

Route::get('/student/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest-student')
                ->name('student.password.reset');

Route::post('/student/reset-password', [NewPasswordController::class, 'submitResetPasswordForm'])
                ->middleware('guest-student')
                ->name('student.password.update');

// Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
//                 ->middleware('auth')
//                 ->name('verification.notice');

// Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//                 ->middleware(['auth', 'signed', 'throttle:6,1'])
//                 ->name('verification.verify');

// Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//                 ->middleware(['auth', 'throttle:6,1'])
//                 ->name('verification.send');

// Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
//                 ->middleware('auth')
//                 ->name('password.confirm');

// Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
//                 ->middleware('auth');

Route::post('/student/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('student')
                ->name('student.logout');
