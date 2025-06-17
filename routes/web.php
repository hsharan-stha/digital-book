<?php

use App\Http\Controllers\CartController;
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

Route::resource('/', controller: HomeController::class);
Route::resource('/cart', controller: CartController::class)->middleware(['auth', 'verified']);
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity')->middleware(['auth', 'verified']);
Route::post('/cart/delete-cart', [CartController::class, 'deleteCartItem'])->name('cart.delete-cart')->middleware(['auth', 'verified']);
Route::resource('/library', controller: LibraryController::class)->middleware(['auth', 'verified']);
Route::resource('/purchases', PurchaseController::class)->middleware(['auth', 'verified']);



Route::get('/reader', function () {
    return view('reader');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('categories', CategoryController::class)->middleware(['auth', 'verified']);
Route::resource('books', BookController::class)->middleware(['auth', 'verified']);
Route::get('/books/{book}/pages', [PageController::class, 'index'])->name('books.pages.index')->middleware(['auth', 'verified']);
Route::post('/pages', [PageController::class, 'store'])->name('pages.store')->middleware(['auth', 'verified']);
Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

require __DIR__ . '/auth.php';
