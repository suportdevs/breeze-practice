<?php

use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ConfirmablePasswordController;
use App\Http\Controllers\Admin\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\EmailVerificationPromptController;
use App\Http\Controllers\Admin\NewPasswordController;
use App\Http\Controllers\Admin\PasswordResetLinkController;
use App\Http\Controllers\Admin\RegisteredUserController;
use App\Http\Controllers\Admin\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest-admin')
                ->name('admin.register');

Route::post('/admin/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest-admin')
                ->name('admin.register');

Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest-admin')
                ->name('admin.login');

Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest-admin')
                ->name('admin.login');

Route::get('/admin/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest-admin:admin')
                ->name('admin.password.request');

Route::post('/admin/forgot-password', [PasswordResetLinkController::class, 'submitForgetPasswordForm'])
                ->middleware('guest-admin:admin')
                ->name('admin.password.email');

Route::get('/admin/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest-admin')
                ->name('admin.password.reset');

Route::post('/admin/reset-password', [NewPasswordController::class, 'submitResetPasswordForm'])
                ->middleware('guest-admin')
                ->name('admin.password.update');

Route::get('/admin/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('admin:admin')
                ->name('admin.verification.notice');

Route::get('/admin/verify-email/{id}/{token}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['admin:admin', 'signed', 'throttle:6,1'])
                ->name('admin.verification.verify');

Route::post('/admin/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['admin:admin', 'throttle:6,1'])
                ->name('admin.verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('auth')
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('auth');

Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('admin')
                ->name('admin.logout');
