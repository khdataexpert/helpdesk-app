<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CompanyStyleController;

// ========== Authentication ==========
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

// ========== Language ==========
Route::get('/lang/{locale}', [LanguageController::class, 'change']); // optional لو عايز تعملها API route

// ========== Dashboard ==========
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // ========== Users ==========
    Route::apiResource('users', UserController::class);
    Route::put('users/{id}/permissions', [UserController::class, 'updatePermissions']);
    Route::post('agents', [UserController::class, 'ListAgents']);
    Route::post('users/logged', [UserController::class, 'updateLogUser']);

    // ========== Teams ==========
    Route::apiResource('teams', TeamController::class);
    Route::get('teams/{team}/members', [TeamController::class, 'members']);
    Route::put('teams/{team}/members', [TeamController::class, 'updateMembers']);


    // ========== Projects ==========
    Route::apiResource('projects', ProjectController::class);
    Route::post('projects/{project}/assign', [ProjectController::class, 'assignToMe']);

    // ========== Tickets ==========
    Route::apiResource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/assign', [TicketController::class, 'assignToMe']);

    // ========== Contracts ==========
    Route::apiResource('contracts', ContractController::class);

    // ========== Invoices ==========
    Route::apiResource('invoices', InvoiceController::class);
    // ========== Companies ==========
    Route::apiResource('companies', CompanyController::class);
    //=========== Roles ==========
    Route::apiResource('roles', RoleController::class);
    //=========== Permissions ==========
    Route::apiResource('permissions', PermissionController::class);
    //=========== Company Styles ==========
    Route::get('companies/{companyId}/style', [CompanyStyleController::class, 'show']);
    Route::post('companies/{companyId}/style', [CompanyStyleController::class, 'storeOrUpdate']);
});
