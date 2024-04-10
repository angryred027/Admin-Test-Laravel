<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', function () {
    return view('/auth/login');
})->name('admin.login');

Route::post('/admin/auth', [\App\Http\Controllers\Admins\AuthController::class, 'login'])->name('admin.auth');

Route::middleware(['auth:api-admins'])->group(function () {
    // ログイン認証が通ってない場合、URLで直接homeにアクセスしても画面が見れないように
    Route::get('/admin/home', function () {
        return view('/admin/home');
    })->name('admin.home');
 });
