<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Public Route
Route::resource('/', HomeController::class);

// Customer Routes (Role: 3)
Route::middleware(['auth', 'verified', 'role:3'])->group(function () {
    Route::resource('/cart', CartController::class);
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::post('/cart/delete-cart', [CartController::class, 'deleteCartItem'])->name('cart.delete-cart');

    Route::resource('/library', LibraryController::class);
    Route::resource('/purchases', PurchaseController::class);

    Route::post('/folder/store', [FolderController::class, 'store'])->name('folder.store');
    Route::post('/folder/rename', [FolderController::class, 'rename'])->name('folder.rename');
    Route::post('/folder/destroy', [FolderController::class, 'destroy'])->name('folder.destroy');
    Route::post('/library/move', [FolderController::class, 'moveBook'])->name('library.move');

    // Static View Route
    Route::view('/reader', 'reader');
});

// Admin/Editor Routes (Role: 1, 2)
Route::middleware(['auth', 'verified', 'role:1,2'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('books', BookController::class);

    Route::get('/books/{book}/pages', [PageController::class, 'index'])->name('books.pages.index');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
});





// Auth routes
require __DIR__ . '/auth.php';