<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KartuStokController;

Route::post('/search-kartu-stok', [KartuStokController::class, 'searchKartuStok'])->name('search.kartu.stok');
Route::group(['middleware' => 'isLogin'], function () {
    Route::get('/', [ViewController::class, 'dashboard'])->name('index');

    Route::get('/satuan', [ViewController::class, 'satuan'])->name('satuanBarang');
    Route::post('/satuan', [SatuanController::class, 'create'])->name('satuan.create');
    Route::delete('/satuan/{id}', [SatuanController::class, 'delete'])->name('satuan.delete');

    Route::get('/barang', [ViewController::class, 'barang'])->name('barang');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/edit/{id}', [BarangController::class, 'edit'])->name('barang.edit');
    Route::post('/barang/update/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::get('/barang/delete/{id}', [BarangController::class, 'delete'])->name('barang.delete');

    Route::get('/kartu-stok', [ViewController::class, 'kartuStok'])->name('kartuStok');
    Route::post('kartu_stok', [KartuStokController::class, 'store'])->name('kartuStok.store');
    Route::get('/kartu-stok/{idkartu_stok}/edit', [KartuStokController::class, 'edit'])->name('kartuStok.edit');
    Route::post('/kartu-stok/{idkartu_stok}/update', [KartuStokController::class, 'update'])->name('kartuStok.update');
    Route::post('/kartu-stok/{idkartu_stok}/delete', [KartuStokController::class, 'delete'])->name('kartuStok.delete');
    Route::get('/kartu-stok/download', [KartuStokController::class, 'downloadCsv'])->name('kartuStok.download');
    Route::get('kartu-stok/history/{id}', [ViewController::class, 'showHistory'])->name('kartuStok.history');
    
    Route::post('/user/update/{iduser}', [AuthController::class, 'update'])->name('user.update');
    Route::post('/user/delete/{iduser}', [AuthController::class, 'delete'])->name('user.delete');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/role', [ViewController::class, 'role'])->name('role');
    Route::get('/test', [ViewController::class, 'test'])->name('test');
});

Route::group(['middleware' => 'NotLogin'], function () {
    Route::get('/login', [ViewController::class, 'login'])->name('login');
    Route::get('/register', [ViewController::class, 'register'])->name('register');
    Route::post('/user/create', [AuthController::class, 'create'])->name('user.create');
    Route::post('/user/login', [AuthController::class, 'loginpost'])->name('user.login');
});
