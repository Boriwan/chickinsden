<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ChickenController;
use App\Http\Controllers\DenController;

use App\Http\Controllers\Admin\AdminChickenController;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/about', [WelcomeController::class, 'about'])->name('about');

Route::get('/chickens', [ChickenController::class, 'index'])->name('chickens');
Route::get('/chickens/{chicken}', [ChickenController::class, 'show'])->name('chickens.show');

Route::get('/dens', [DenController::class, 'index'])->name('dens');
Route::get('/dens/{den}', [DenController::class, 'show'])->name('dens.show');

// Management routes
Route::get('/admin/chickens', [AdminChickenController::class, 'index'])->name('admin.chickens');
Route::get('/admin/chickens/create', [AdminChickenController::class, 'create'])->name('admin.chickens.create');
Route::post('/admin/chickens', [AdminChickenController::class, 'store'])->name('admin.chickens.store');


Route::get('/dashboard', function () {
    return view('userzone.dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
