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

  //    $token = Token::first()->qb_token;
  //    dd($token);

  //  if  (count($token) == 0){
  //   dd('no record');
  //  } else {
  //   dd('record exists');
  //  };

    return view('welcome');
})->name('mtaani');
Route::get('/quickbook',[QuickBooksController::class,'index']);
Route::get('/callback',[QuickBooksController::class,'callback']);
Route::get('/create/account',[QuickBooksController::class,'testAccount']);
Route::get('/create_customer', [QuickBooksController::class,'createAccount']);
