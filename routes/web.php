<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/add', [App\Http\Controllers\addController::class, 'add'])->name('add');

Route::get('/setting', [App\Http\Controllers\settingController::class, 'setting'])->name('setting');

Route::post('add/category',[App\Http\Controllers\addController::class, 'addCategory']);

Route::post('add/transaction',[App\Http\Controllers\addController::class, 'addTransaction']);

Route::get('/category/edit/{id}', [App\Http\Controllers\settingController::class, 'editOne']);

Route::post('/update/category', [App\Http\Controllers\settingController::class, 'updateOne']);

Route::post('/delete/category', [App\Http\Controllers\settingController::class, 'deleteOne']);

Route::get('/transaction/{id}', [App\Http\Controllers\statisticsController::class, 'editOneTransaction']);

Route::post('/delete/transaction', [App\Http\Controllers\statisticsController::class, 'deleteTransaction']);

Route::post('/update/transaction', [App\Http\Controllers\statisticsController::class, 'updateTransaction']);

//Route::get('/search/comment', [App\Http\Controllers\statisticsController::class, 'searchComment']);

Route::get('/user', [App\Http\Controllers\userController::class, 'editUser']);

Route::post('/user/update', [App\Http\Controllers\userController::class, 'updateUser']);

Route::get('/home', function () {
    return redirect('/');
});
