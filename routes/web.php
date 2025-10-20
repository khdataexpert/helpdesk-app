<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\auth\authcontroller;
use App\Http\Controllers\dashboardcontroller;

Route::get('/', function () {
          if (Auth::check()) {
            return redirect()->route('dashboard'); // أو أي صفحة عايزها
        }
  return view('welcome');
});
Route::get('lang/{locale}', [LanguageController::class, 'change'])->name('lang.switch');
Route::post('/login', [authcontroller::class, 'login'])->name('login')->middleware('guest');
Route::prefix('dashboard')->middleware('auth')->group(function () {
  Route::get('/', [dashboardcontroller::class, 'index'])->name('dashboard');
  Route::resource('roles', RoleController::class);
  Route::resource('users', UserController::class);
  Route::resource('teams', TeamController::class);
  Route::resource('projects', ProjectController::class);
  Route::resource('tickets', TicketController::class);
  Route::resource('contracts', ContractController::class);
  Route::resource('invoices', InvoiceController::class);
  Route::post('/projects/{project}/assignToMe', [ProjectController::class, 'assignToMe'])->name('projects.assignToMe');
  Route::get('/users/{id}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
  Route::put('/users/{id}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
  Route::get('teams/{team}/members/edit', [TeamController::class, 'editMembers'])->name('teams.edit_members');
  Route::put('teams/{team}/members', [TeamController::class, 'updateMembers'])->name('teams.update_members');
  Route::post('teckets/{ticket}/assignToMe', [TicketController::class, 'assignToMe'])->name('tickets.assignToMe');
  Route::post('/logout', [authcontroller::class, 'logout'])->name('logout');
});
