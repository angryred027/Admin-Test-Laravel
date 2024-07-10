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
        Route::get('/sample2', [\App\Http\Controllers\Admins\AdminSampleController::class, 'sample2'])->name('admin.sample2');
        Route::get('/sampleImageUploader1', [\App\Http\Controllers\Admins\AdminSampleController::class, 'sampleImageUploader1'])->name('admin.sampleImageUploader1');
        Route::post('/sampleImageUploader1', [\App\Http\Controllers\Admins\AdminSampleController::class, 'sampleImageUploader1Post'])->name('admin.sampleImageUploader1.post');
        Route::get('/sampleImageUploader1/create', [\App\Http\Controllers\Admins\AdminSampleController::class, 'sampleImageUploader1Create'])->name('admin.sampleImageUploader1.create');
        Route::get('/sampleImageUploader1/edit', [\App\Http\Controllers\Admins\AdminSampleController::class, 'sampleImageUploader1Edit'])->name('admin.sampleImageUploader1.edit');
        Route::get('/sampleImageUploader1/createModal', [\App\Http\Controllers\Admins\AdminSampleController::class, 'sampleImageUploader1CreateModal'])->name('admin.sampleImageUploader1.createModal');
        Route::post('/sampleImageUploader1/createModal', [\App\Http\Controllers\Admins\AdminSampleController::class, 'sampleImageUploader1CreateModalPost'])->name('admin.sampleImageUploader1.createModal.post');
        Route::post('/logout', [\App\Http\Controllers\Admins\AuthController::class, 'logout'])->name('admin.logout');
    });
 });
