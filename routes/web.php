<?php

use App\Http\Controllers\Admin\AdminBreedController;
use App\Http\Controllers\Admin\AdminChickenController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\ChickenController;
use App\Http\Controllers\Userzone\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
|
| Reachable by anyone, including guests.
|
*/

Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/about', [WelcomeController::class, 'about'])->name('about');

Route::get('/breeds', [BreedController::class, 'index'])->name('breeds.index');
Route::get('/breeds/{breed}', [BreedController::class, 'show'])->name('breeds.show');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
|
| Any signed-in user. Owners see their own chickens, admins see all.
|
*/

Route::middleware('auth')->group(function () {
    Route::resource('chickens', ChickenController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
|
| Restricted to users with is_admin = true.
|
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/chickens', [AdminChickenController::class, 'index'])->name('admin.chickens.index');

    Route::get('/admin/breeds', [AdminBreedController::class, 'index'])->name('admin.breeds.index');
});

require __DIR__.'/auth.php';
