<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactoFirebaseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;


/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::view('/', 'index');

Route::view('/contacto', 'contact');

Route::post('/guardar-contacto', [ContactController::class, 'store']);

Route::get('/leer-contactos', [ContactController::class, 'index']);



/*
|--------------------------------------------------------------------------
| Login Firebase
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



/*
|--------------------------------------------------------------------------
| Registro Firebase
|--------------------------------------------------------------------------
*/

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'register']);



/*
|--------------------------------------------------------------------------
| Dashboard Firebase
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

Route::get('/dashboard/users/{id}/edit', [AdminController::class, 'editUser'])->name('dashboard.user.edit');

Route::put('/dashboard/users/{id}', [AdminController::class, 'updateUser'])->name('dashboard.user.update');

Route::delete('/dashboard/users/{id}', [AdminController::class, 'deleteUser'])->name('dashboard.user.delete');



/*
|--------------------------------------------------------------------------
| Perfil Firebase
|--------------------------------------------------------------------------
*/

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');



/*
|--------------------------------------------------------------------------
| Otras rutas
|--------------------------------------------------------------------------
*/

Route::get('/usuarios', [HomeController::class, 'users']);

Route::get('/home', [HomeController::class, 'index'])->name('home');