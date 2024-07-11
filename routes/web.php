<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;

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

Route::get('/', function () { return view('welcome'); })->name('welcome');

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

Route::group(['middleware' => ['auth', 'adminorvendor']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

Route::group(['middleware' => ['auth', 'admin']], function () {
    // users crud operation
    Route::prefix('users')->group(function () {
        Route::get('list', [UsersController::class, 'index'])->name('users.list');
        Route::get('show/{id}', [UsersController::class, 'show'])->name('users.show');
        Route::get('create', function() { return view('users.create'); })->name('users.create');
        Route::post('store', [UsersController::class, 'store'])->name('users.store');
        Route::get('edit/{id}', [UsersController::class, 'edit'])->name('users.edit');
        Route::post('update/{id}', [UsersController::class, 'update'])->name('users.update');
        Route::get('destroy/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
    });
});

Route::group(['middleware' => ['auth', 'adminorvendor']], function () {
    // category crud operation
    Route::prefix('category')->group(function () {
        Route::get('list', [CategoryController::class, 'index'])->name('category.list');
        Route::get('show/{id}', [CategoryController::class, 'show'])->name('category.show');
        Route::get('create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('store', [CategoryController::class, 'store'])->name('category.store');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('update/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::get('destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

        Route::get('status/{id}', [CategoryController::class, 'status'])->name('category.status');
    });

    // product crud operation
    Route::prefix('product')->group(function () {
        Route::get('list', [ProductController::class, 'index'])->name('product.list');
        Route::get('show/{id}', [ProductController::class, 'show'])->name('product.show');
        Route::get('create', [ProductController::class, 'create'])->name('product.create');
        Route::post('store', [ProductController::class, 'store'])->name('product.store');
        Route::get('edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('update/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::get('destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

        Route::get('removeimg/{id}', [ProductController::class, 'removeimg'])->name('product.removeimg');
        Route::get('status/{id}', [ProductController::class, 'status'])->name('product.status');
        Route::get('childcategory', [ProductController::class, 'childcategory'])->name('product.childcategory');
        Route::get('export', [ProductController::class, 'export'])->name('product.export');
        Route::post('import', [ProductController::class, 'import'])->name('product.import');
    });

    // brand crud operation
    Route::prefix('brand')->group(function () {
        Route::get('list', [BrandController::class, 'index'])->name('brand.list');
        Route::get('show/{id}', [BrandController::class, 'show'])->name('brand.show');
        Route::get('create', function() { return view('brand.create'); })->name('brand.create');
        Route::post('store', [BrandController::class, 'store'])->name('brand.store');
        Route::get('edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::post('update/{id}', [BrandController::class, 'update'])->name('brand.update');
        Route::get('destroy/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');

        Route::get('status/{id}', [BrandController::class, 'status'])->name('brand.status');
    });
});

/* // trash data
Route::get('trash', [UsersController::class, 'trashrecord'])->name('users.trash');
Route::get('trash', [CategoryController::class, 'trashrecord'])->name('category.trash');
Route::get('trash', [ProductController::class, 'trashrecord'])->name('product.trash');

// restore data
Route::get('restore/{id}', [UsersController::class, 'restore'])->name('users.restore');
Route::get('restore/{id}', [CategoryController::class, 'restore'])->name('category.restore');
Route::get('restore/{id}', [ProductController::class, 'restore'])->name('product.restore');

// permanent delete
Route::get('delete/{id}', [UsersController::class, 'delete'])->name('users.delete');
Route::get('delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');
Route::get('delete/{id}', [ProductController::class, 'delete'])->name('product.delete'); */