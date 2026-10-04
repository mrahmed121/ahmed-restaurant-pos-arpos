<?php
use App\Http\Controllers\Api\V1\AuditLogController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\KitchenController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ReportController;
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

        Route::get('branches', [BranchController::class, 'index'])->middleware('permission:branches.view');
        Route::post('branches', [BranchController::class, 'store'])->middleware('permission:branches.manage');

        Route::get('menu/categories', [MenuController::class, 'categories'])->middleware('permission:menu.view');
        Route::get('menu/items', [MenuController::class, 'items'])->middleware('permission:menu.view');
        Route::post('menu/items', [MenuController::class, 'storeItem'])->middleware('permission:menu.manage');

        Route::get('orders', [OrderController::class, 'index'])->middleware('permission:orders.view');
        Route::post('orders', [OrderController::class, 'store'])->middleware('permission:orders.create');
        Route::get('orders/{order}', [OrderController::class, 'show'])->middleware('permission:orders.view');
        Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->middleware('permission:orders.manage');
        Route::post('orders/{order}/payments', [OrderController::class, 'addPayment'])->middleware('permission:payments.create');

        Route::get('kitchen/tickets', [KitchenController::class, 'tickets'])->middleware('permission:kitchen.view');
        Route::put('kitchen/tickets/{item}', [KitchenController::class, 'updateTicket'])->middleware('permission:kitchen.manage');

        Route::get('reports/sales', [ReportController::class, 'sales'])->middleware('permission:reports.view');

        Route::get('users', [UserController::class, 'index'])->middleware('permission:users.view');
        Route::get('settings', [SettingController::class, 'index'])->middleware('permission:settings.view');
        Route::put('settings', [SettingController::class, 'update'])->middleware('permission:settings.manage');
        Route::get('audit-logs', [AuditLogController::class, 'index'])->middleware('permission:audit.view');
    });
});
