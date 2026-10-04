<?php
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\AuditLogController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\DoctorController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\PatientController;
use App\Http\Controllers\Api\V1\PharmacyController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;
Route::prefix('v1')->group(function () {
    Route::get('health', HealthController::class);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/refresh', [AuthController::class, 'refresh']);
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::get('dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard.view');
        Route::get('patients', [PatientController::class, 'index'])->middleware('permission:patients.view');
        Route::post('patients', [PatientController::class, 'store'])->middleware('permission:patients.manage');
        Route::get('patients/{patient}', [PatientController::class, 'show'])->middleware('permission:patients.view');
        Route::get('doctors', [DoctorController::class, 'index'])->middleware('permission:doctors.view');
        Route::get('appointments', [AppointmentController::class, 'index'])->middleware('permission:appointments.view');
        Route::post('appointments', [AppointmentController::class, 'store'])->middleware('permission:appointments.manage');
        Route::put('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->middleware('permission:appointments.manage');
        Route::get('medicines', [PharmacyController::class, 'medicines'])->middleware('permission:pharmacy.view');
        Route::get('users', [UserController::class, 'index'])->middleware('permission:users.view');
        Route::get('settings', [SettingController::class, 'index'])->middleware('permission:settings.view');
        Route::put('settings', [SettingController::class, 'update'])->middleware('permission:settings.manage');
        Route::get('audit-logs', [AuditLogController::class, 'index'])->middleware('permission:audit.view');
    });
});
