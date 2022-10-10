<?php

use App\Http\Controllers\QuickBooksController;
use App\Models\Token;
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
    return view('welcome');
})->name('mtaani');
// initiate connection to quickbooks 
Route::get('/quickbooks/connect', [QuickBooksController::class, 'initiateConnection'])->name('quickbooks.initiate');
// quickbooks routes
Route::get('/quickbooks', [QuickBooksController::class, 'index'])->name('quickbooks.index');
Route::get('/quickbooks/callback', [QuickBooksController::class, 'callback'])->name('quickbooks.callback');
Route::get('/quickbooks/refresh', [QuickBooksController::class, 'refresh'])->name('quickbooks.refresh');
Route::get('/quickbooks/revoke', [QuickBooksController::class, 'revoke'])->name('quickbooks.revoke');
// quickbooks accounts routes
Route::get('/quickbooks/account', [QuickBooksController::class, 'accounts'])->name('quickbooks.account');
Route::get('/quickbooks/account/create', [QuickBooksController::class, 'createAccount'])->name('quickbooks.account.create');
Route::post('/quickbooks/account/store', [QuickBooksController::class, 'storeAccount'])->name('quickbooks.account.store');
Route::get('/quickbooks/account/edit/{id}', [QuickBooksController::class, 'editAccount'])->name('quickbooks.account.edit');
Route::post('/quickbooks/account/update/{id}', [QuickBooksController::class, 'updateAccount'])->name('quickbooks.account.update');
Route::get('/quickbooks/account/delete/{id}', [QuickBooksController::class, 'deleteAccount'])->name('quickbooks.account.delete');
// quickbooks customers routes



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
