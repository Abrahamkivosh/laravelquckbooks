<?php

use App\Http\Controllers\QuickBooksController;
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
});
Route::get('/quickbook',[QuickBooksController::class,'index']);
Route::get('/callback',[QuickBooksController::class,'callback']);
Route::get('/create/account',[QuickBooksController::class,'createAccount']);
