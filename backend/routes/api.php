<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get_invoices', [InvoiceController::class, 'get_invoices']);
Route::get('search_invoice', [InvoiceController::class, 'search_invoice']);
Route::get('add_invoice', [InvoiceController::class, 'add_invoice']);
Route::get('get_customers', [InvoiceController::class, 'get_customers']);
Route::get('get_products', [InvoiceController::class, 'get_products']);
Route::post('store_invoice', [InvoiceController::class, 'store']);
Route::get('get_invoice/{id}', [InvoiceController::class, 'get_invoice']);
Route::get('delete_invoice_items/{id}', [InvoiceController::class, 'delete_invoice_items']);
Route::post('update_invoice/{id}', [InvoiceController::class, 'update_invoice']);
Route::get('delete_invoice/{id}', [InvoiceController::class, 'delete_invoice']);

///////////////------CRUD API-------///////////////


Route::get('/get_all_products', [ProductController::class, 'get_all_products']);
Route::post('/add_product', [ProductController::class, 'store']);
Route::get('/get_product/{id}', [ProductController::class, 'show']);
Route::post('/update_product/{id}', [ProductController::class, 'update']);
Route::get('/delete_product/{id}', [ProductController::class, 'delete']);
