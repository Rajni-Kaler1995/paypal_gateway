<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
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

// Route::get('/', function () {
//     return view('student/index');
// });

Route::get('/', function () {
    return view('paypal_payment.index');
});

Route::post('pay', [PaymentController::class, 'pay'])->name('payment');
Route::get('success', [PaymentController::class, 'success']);
Route::get('error', [PaymentController::class, 'error']);

// Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
// Route::get('/add-invoice', [InvoiceController::class, 'addInvoice'])->name('add-invoice');
// Route::get('/getUnits/{itemId}', [InvoiceController::class, 'getUnits'])->name('get-units');
// Route::post('/insert-invoice', [InvoiceController::class, 'insertInvoice'])->name('insert-invoice');

// Route::get('/getUnits/{itemId}', 'ItemController@getUnits');



// Route::post('/createStudent', [StudentController::class, 'createStudent']);
// Route::get('/duplicateRecords', [StudentController::class, 'duplicateRecords'])->name('student.duplicateRecords');
