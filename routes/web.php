<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;

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
Route::get('/detail/{book_id}/view', [HomeController::class, 'details'])->name('detail.view');


// Customer Routes (Role: 3)
Route::middleware(['auth', 'verified', 'role:3'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::post('/cart/delete-cart', [CartController::class, 'deleteCartItem'])->name('cart.delete-cart');

    Route::resource('/library', LibraryController::class);
    Route::resource('/purchases', PurchaseController::class);
    Route::get('/reader/{book_id}/reading', [ReaderController::class, "index"]);
    Route::post('/reader/session/save', [ReaderController::class, 'saveSession']);

    Route::post('/folder/store', [FolderController::class, 'store'])->name('folder.store');
    Route::post('/folder/rename', [FolderController::class, 'rename'])->name('folder.rename');
    Route::post('/folder/destroy', [FolderController::class, 'destroy'])->name('folder.destroy');
    Route::post('/library/move', [FolderController::class, 'moveBook'])->name('library.move');
    Route::post('/library/sort', [FolderController::class, 'sortBook'])->name('library.sort');
    Route::get('/library/folder/{name}/books', [LibraryController::class, 'getBooksByFolder']);

});

// Admin/Editor Routes (Role: 1, 2)
Route::middleware(['auth', 'verified', 'role:1,2'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::resource('books', BookController::class);

    Route::get('/books/{book}/pages', [PageController::class, 'index'])->name('books.pages.index');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
    Route::post('/pages/insert', [PageController::class, 'store'])->name('pages.store');

    Route::get('/purchase/list', [PurchaseController::class, 'list'])->name('purchase.list');
    Route::put('/purchase/update', [PurchaseController::class, 'update'])->name('purchase.update');
});

Route::middleware(['auth', 'verified', 'role:1'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('users', UserController::class);
});






// Auth routes
require __DIR__ . '/auth.php';