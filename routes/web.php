<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PengadaanController;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin', [ViewController::class, 'dashboard'])->name('index.admin');
    Route::get('/', [ViewController::class, 'dashboarduser'])->name('index.user');

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
    Route::post('/search-kartu-stok', [KartuStokController::class, 'searchKartuStok'])->name('search.kartu.stok');

    Route::get('/addrole', [ViewController::class, 'addrole'])->name('addrole');
    Route::post('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::delete('/roles/delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');

    Route::get('/adduser', [ViewController::class, 'adduser'])->name('adduser');
    Route::post('/users/store', [UserController::class, 'create'])->name('users.store');
    Route::delete('/users/destroy/{id}', [UserController::class, 'delete'])->name('users.destroy');

    Route::get('/addvendor', [ViewController::class, 'addvendor'])->name('addvendor');
    Route::post('/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
    Route::delete('/vendor/delete/{id}', [VendorController::class, 'delete'])->name('vendor.delete');

    Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index');
    Route::get('/pengadaan/create', [PengadaanController::class, 'create'])->name('pengadaan.create');
    Route::get('/pengadaan/caribarang', [PengadaanController::class, 'caribarang'])->name('pengadaan.caribarang');
    Route::post('/pengadaan/store', [PengadaanController::class, 'store'])->name('pengadaan.store');
    Route::post('/caribarang', [PengadaanController::class, 'caribarang'])->name('caribarang');
    Route::get('/pengadaan/detail/{id}', [PengadaanController::class, 'detail'])->name('pengadaan.detail');
    Route::get('/pengadaan/detailvalidasi/{id}', [PengadaanController::class, 'detailvalidasi'])->name('pengadaan.detailvalidasi');



    Route::post('/logout', [AuthController::class, 'logoutakun'])->name('logout');

});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [ViewController::class, 'login'])->name('login');
    Route::get('/register', [ViewController::class, 'register'])->name('register');
    Route::post('/user/create', [AuthController::class, 'create'])->name('user.create');
    Route::post('/user/login', [AuthController::class, 'loginpost'])->name('user.login');
    Route::get('/dataTable', function () {
        return view('dataTable');
    });
    Route::get('/lineCharts', function () {
        return view('lineCharts');
    });
    Route::get('/htmlToPdf', function () {
        return view('htmlToPdf');
    });
});
