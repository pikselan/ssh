<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SshController;
use App\Http\Controllers\AsbController;
use App\Http\Controllers\HspkController;

use App\Http\Controllers\ViewSshController;
use App\Http\Controllers\ViewAsbController;
use App\Http\Controllers\ViewHspkController;

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
    // return view('welcome');
    // return redirect()->route('admin');
    return redirect('admin');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    
    Route::get('view-ssh', [ViewSshController::class, 'index'])->middleware('admin.user');
    Route::post('view-ssh', [ViewSshController::class, 'index'])->middleware('admin.user');
    Route::get('view-ssh/getdata', [ViewSshController::class, 'getData'])->name('getData')->middleware('admin.user');
    Route::get('view-ssh/getsubrincianobjek', [ViewSshController::class, 'getSubRincianObjek'])->name('getSubRincianObjek')->middleware('admin.user');
    Route::get('view-ssh/getsubsubrincianobjek', [ViewSshController::class, 'getSubSubRincianObjek'])->name('getSubSubRincianObjek')->middleware('admin.user');

    Route::get('view-asb', [ViewAsbController::class, 'index'])->middleware('admin.user');
    Route::get('view-asb/getdata', [ViewAsbController::class, 'getData'])->name('getDataAsb')->middleware('admin.user');

    Route::get('view-hspk', [ViewHspkController::class, 'index'])->middleware('admin.user');
    Route::get('view-hspk/getdata', [ViewHspkController::class, 'getData'])->name('getDataHspk')->middleware('admin.user');
});

// Route::post('/admin/ssh/import', 'SshController@import')->middleware('admin.user');
Route::post('/admin/ssh/import', [SshController::class, 'import'])->middleware('admin.user');
Route::post('/admin/asbs/import', [AsbController::class, 'import'])->middleware('admin.user');
Route::post('/admin/hspk/import', [HspkController::class, 'import'])->middleware('admin.user');