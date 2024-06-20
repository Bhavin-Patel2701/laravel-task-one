<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/laravel', function () {
    return view('welcome');
})->name('laravel');

Auth::routes([
    'login' => true,     // Enable login route
    'logout' => true,    // Enable logout route
    'register' => true,  // Disable registration route
    'reset' => true,     // Enable password reset routes
    'confirm' => true,   // Enable password confirmation routes
    'verify' => true     // Enable email verification routes
]);
// Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// users crud operation
Route::prefix('users')->group(function () {
    Route::get('list', [UsersController::class, 'index'])->name('users.list');
    Route::get('show/{id}', [UsersController::class, 'show'])->name('users.show');

    /* Route::get('create', function() { return view('users.create'); })->name('users.create');
    Route::post('store', [CrudController::class, 'store'])->name('users.store');
    Route::get('edit/{id}', [CrudController::class, 'edit'])->name('users.edit');
    Route::put('update/{id}', [CrudController::class, 'update'])->name('users.update');
    Route::get('destroy/{id}', [CrudController::class, 'destroy'])->name('users.destroy'); */

    Route::get('write', function() { return "D"; })->name('users.write');
});