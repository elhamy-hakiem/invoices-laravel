<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesArchiveController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoiceAttachmentsController;
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

// Authentication Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('auth.login');
});

// Start Invoices routes
Route::resource('invoices', InvoicesController::class);
Route::get('invoices/status/{status}', [InvoicesController::class ,'status']);
Route::get('/invoices/edit/{id}', [InvoicesController::class ,'edit']);
Route::post('/invoices/update', [InvoicesController::class ,'update']);
Route::post('/invoices/destroy', [InvoicesController::class ,'destroy']);
Route::post('/invoices/changePayment', [InvoicesController::class ,'paymentStatus']);


// Start Invoices Archived routes
Route::resource('invoicesArchived', InvoicesArchiveController::class);


//Section Products routes
Route::get('/section/{id}', [InvoicesController::class ,'getproducts']);

//Invoices Details routes
Route::get('/invoiceDetails/{id}', [InvoicesDetailsController::class,'show']);
// End Invoices routes

//Start Invoices Attachements routes
Route::get('/invoiceDetails/open_file/{invoice_number}/{fileId}', [InvoicesDetailsController::class,'open_file']);
Route::post('/invoiceDetails/delete_file', [InvoicesDetailsController::class,'delete_file']);
//End Invoices Attachements routes

// Start Sections routes
Route::resource('sections', SectionsController::class);
// End Sections routes

// Start Sections routes
Route::resource('products',ProductsController::class);
// End Sections routes


Route::get('/{page}', [AdminController::class ,'index']);
