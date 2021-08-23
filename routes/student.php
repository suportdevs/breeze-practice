<?php

use App\Http\Controllers\Student\AuthenticatedSessionController;
use App\Http\Controllers\Student\ConfirmablePasswordController;
use App\Http\Controllers\Student\EmailVerificationNotificationController;
use App\Http\Controllers\Student\EmailVerificationPromptController;
use App\Http\Controllers\Student\NewPasswordController;
use App\Http\Controllers\Student\PasswordResetLinkController;
use App\Http\Controllers\Student\RegisteredUserController;
use App\Http\Controllers\Admin\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/student/register', [RegisteredUserController::class, 'create'])
                ->middleware('gueststudent')
                ->name('student.register');

Route::post('/student/register', [RegisteredUserController::class, 'store'])
                ->middleware('gueststudent')
                ->name('student.register');

Route::get('/student/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('gueststudent')
                ->name('student.login');

Route::post('/student/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('gueststudent')
                ->name('student.login');

Route::get('/student/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('gueststudent:student')
                ->name('student.password.request');

Route::post('/student/forgot-password', [PasswordResetLinkController::class, 'submitForgetPasswordLink'])
                ->middleware('gueststudent:student')
                ->name('student.password.email');

Route::get('/student/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('gueststudent')
                ->name('student.password.reset');

Route::post('/student/reset-password', [NewPasswordController::class, 'submitResetPasswordForm'])
                ->middleware('gueststudent')
                ->name('student.password.update');

Route::get('/student/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('student:student')
                ->name('student.verification.notice');

Route::get('/student/verify-email/{id}/{hash}', [RegisteredUserController::class, 'studentEmailVerify'])
                ->middleware(['student:student', 'signed', 'throttle:6,1'])
                ->name('student.verification.verify');

Route::post('/student/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['student:student', 'throttle:6,1'])
                ->name('student.verification.send');

Route::get('/student/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('student:student')
                ->name('student.password.confirm');

Route::post('/student/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('student:student');

Route::post('/student/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('student')
                ->name('student.logout');
