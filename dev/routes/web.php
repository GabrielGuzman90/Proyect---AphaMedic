<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PeticionController;
use App\Http\Controllers\AdminPeticionController;
use App\Http\Controllers\PedidoController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::view('/', 'index')->name('index');
Route::view('/contacto', 'contact')->name('contacto');

Route::post('/guardar-contacto', [ContactController::class, 'store']);
Route::view('/cruz-roja', 'lugares.cruz-roja')->name('cruzroja');
Route::view('/about-us', 'about-us')->name('about-us');
Route::view('/terminos-condicones', 'terminoscondiciones')->name('terminoscondiciones');
Route::view('/avisosprivacidad', 'avisosprivacidad')->name('avisosprivacidad');
Route::get('/home', [MedicamentoController::class,'home'])->name('home');

Route::get('/categoria/{categoria}', 
    [MedicamentoController::class, 'verCategoria']
)->name('categoria.ver');



Route::get('/mis-pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| REGISTRO
|--------------------------------------------------------------------------
*/

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| PERFIL (requiere sesión normal si luego agregas auth)
|--------------------------------------------------------------------------
*/

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

/*
|--------------------------------------------------------------------------
| MEDICAMENTOS
|--------------------------------------------------------------------------
*/

Route::resource('medicamentos', MedicamentoController::class);
Route::post('/guardar/{id}', [MedicamentoController::class,'guardar']);

/*
|--------------------------------------------------------------------------
| CARRITO Y PETICIÓN
|--------------------------------------------------------------------------
*/

Route::middleware('auth.firebase')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::get('/cart/add/{id}', [CartController::class, 'add'])
        ->name('cart.add');

    Route::post('/cart/update/{id}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::post('/peticion', [PeticionController::class, 'store'])
        ->name('peticion.store');

});

/*
|--------------------------------------------------------------------------
| RUTAS SOLO ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CONTACTOS ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/leer-contactos', [ContactController::class, 'index'])
        ->name('contactos.index');

    Route::delete('/contacto/{id}', [ContactController::class, 'eliminar'])
        ->name('contacto.delete');

    Route::put('/contacto/{id}/status/{status}', [ContactController::class, 'cambiarStatus'])
        ->name('contacto.status');

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/users/{id}/edit', 
        [AdminController::class, 'editUser'])
        ->name('dashboard.user.edit');

    Route::put('/dashboard/users/{id}', 
        [AdminController::class, 'updateUser'])
        ->name('dashboard.user.update');

    Route::delete('/dashboard/users/{id}', 
        [AdminController::class, 'deleteUser'])
        ->name('dashboard.user.delete');

    /*
    |--------------------------------------------------------------------------
    | PANEL ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get('/admin', function () {
        return view('administrador');
    })->name('admin.panel');

    /*
    |--------------------------------------------------------------------------
    | ADMIN PETICIONES
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/peticiones', 
        [AdminPeticionController::class, 'index'])
        ->name('admin.peticiones');

    Route::get('/admin/peticiones/aceptados', 
        [AdminPeticionController::class, 'aceptados'])
        ->name('admin.peticiones.aceptados');

    Route::get('/admin/peticiones/rechazados', 
        [AdminPeticionController::class, 'rechazados'])
        ->name('admin.peticiones.rechazados');

    Route::patch('/admin/peticiones/{id}/{estado}', 
        [AdminPeticionController::class, 'cambiarEstado'])
        ->name('admin.peticiones.estado');

});