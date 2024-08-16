<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/v1', function () {
    return response()->json(['message' => 'Welcome to API version 1']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function ($router) {
    /**Items**/
    Route::get('/items',[ItemController::class, 'index'])->name('items.index');
    Route::get('/items/{id}',[ItemController::class, 'show'])->name('items.show');
    Route::post('/items', [ItemController::class,'store'])->name('items');
    Route::patch('/items/{id}',[ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class,'destroy'])->name('items.destroy');

    /**Invoices**/
    Route::get('/invoice',[InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/invoice/{id}',[InvoiceController::class, 'show'])->name('invoice.show');
    Route::post('/invoice', [InvoiceController::class,'store'])->name('invoice');
    Route::patch('/invoice/{id}',[InvoiceController::class, 'update'])->name('invoice.update');
    Route::delete('/invoice/{id}', [InvoiceController::class,'destroy'])->name('invoice.destroy');
    Route::get('/invoice/pdf/{id}',[InvoiceController::class, 'generatepdf']);

});
