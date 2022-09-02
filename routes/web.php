<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\ProductsController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

// Authentication Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// Start Invoices routes
Route::resource('invoices', InvoicesController::class);

Route::get('/section/{id}', [InvoicesController::class ,'getproducts']);
// End Invoices routes

// Start Sections routes
Route::resource('sections', SectionsController::class);
// End Sections routes

// Start Sections routes
Route::resource('products',ProductsController::class);
// End Sections routes


Route::get('/{page}', [AdminController::class ,'index']);
