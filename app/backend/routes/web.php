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


// 未ログイン
Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', function () {
        return view('/auth/login');
    })->name('admin.login');

    Route::post('/auth', [\App\Http\Controllers\Admins\AuthController::class, 'login'])->name('admin.auth');

});

Route::middleware(['auth:api-admins'])->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/home', function () {
            return view('/admin/home');
        })->name('admin.home');

        Route::get('/test', [\App\Http\Controllers\Admins\AdminSampleController::class, 'test'])->name('admin.test');
        Route::get('/sample', [\App\Http\Controllers\Admins\AdminSampleController::class, 'sample'])->name('admin.sample');
        Route::get('/sample1', [\App\Http\Controllers\Admins\AdminSampleController::class, 'sample1'])->name('admin.sample1');
        Route::post('/logout', [\App\Http\Controllers\Admins\AuthController::class, 'logout'])->name('admin.logout');
    });
 });
