<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardDlcController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenerimaEmailController;
use App\Http\Controllers\BodyEmailController;
use App\Http\Controllers\PeriodeTnaController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\DepartmentController;

// Auth Routes
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login-dlc', [AuthController::class, 'loginDlc'])->name('login.dlc');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Perlu Login)
Route::middleware(['checkLogin'])->group(function () {
    Route::get('/', function () {
        if (session('role') === 'DLC') {
            return redirect()->route('dashboarddlc');
        }
        return redirect()->route('dashboard');
    })->name('home');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboarddlc', [DashboardDlcController::class, 'index'])->name('dashboarddlc');

    // ==========DLC==========
    // Staff / Member Management
    Route::get('/member-list', [UsersController::class, 'dlc'])->name('member-list');
    Route::get('/immediate-manager', function () {
        return redirect()->route('member-list');
    })->name('immediate-manager');

    Route::get('/staff/export', [UsersController::class, 'exportStaff'])->name('staff.export');
    Route::get('/staff/template', [UsersController::class, 'templateStaff'])->name('staff.template');
    Route::post('/staff/import', [UsersController::class, 'importStaff'])->name('staff.import');
    Route::get('/staff-training/template', [UsersController::class, 'templateStaffTraining'])->name('staffTraining.template');
    Route::post('/staff-training/import', [UsersController::class, 'importStaffTraining'])->name('staffTraining.import');

    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UsersController::class, 'store'])->name('users.store');
    Route::get('/staff/detail/{id_staff}', [UsersController::class, 'detail'])->name('staff.detail');
    Route::get('/staff/edit/{id}', [UsersController::class, 'editStaff'])->name('staff.edit');
    Route::put('/staff/update/{id}', [UsersController::class, 'updateStaff'])->name('staff.update');
    Route::delete('/staff/destroy/{id}', [UsersController::class, 'destroyStaff'])->name('staff.destroy');
    Route::delete('/staff/bulk-destroy', [UsersController::class, 'bulkDestroyStaff'])->name('staff.bulkDestroy');

    // Training Management (CRUD)
    Route::resource('training', TrainingController::class);

    // Divisi Management (CRUD)
    Route::resource('divisi', DivisiController::class);

    // Department Management (CRUD)
    Route::resource('department', DepartmentController::class);

    // Email & TNA Settings
    Route::get('/penerima-email', [PenerimaEmailController::class, 'index'])->name('penerima-email');
    Route::post('/penerima-email', [PenerimaEmailController::class, 'store'])->name('penerima-email.store');
    Route::get('/body-email', [BodyEmailController::class, 'index'])->name('body-email');
    Route::post('/body-email', [BodyEmailController::class, 'store'])->name('body-email.store');
    Route::get('/periode-tna', [PeriodeTnaController::class, 'index'])->name('periode-tna');
    Route::post('/periode-tna/save-period', [PeriodeTnaController::class, 'savePeriod'])->name('periode-tna.savePeriod');
    Route::post('/periode-tna/close-tna', [PeriodeTnaController::class, 'closeTna'])->name('periode-tna.closeTna');
    Route::post('/periode-tna/send-email', [PeriodeTnaController::class, 'sendEmail'])->name('periode-tna.sendEmail');
    // ==========DLC==========

    // ==========DEPHEAD / IMMEDIATE MANAGER==========
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/users/detail/{id_staff}', [UsersController::class, 'detail'])->name('users.detail');
    Route::post('/staff-training/update', [UsersController::class, 'update'])->name('staffTraining.update');
    Route::get('/users/permintaan', [UsersController::class, 'permintaan'])->name('users.permintaan');
    Route::get('/users/terlaksana', [UsersController::class, 'terlaksana'])->name('users.terlaksana');
    Route::get('/users/tidakhadir', [UsersController::class, 'tidakhadir'])->name('users.tidakhadir');
    // ==========DEPHEAD / IMMEDIATE MANAGER==========

});