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
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\MarginController;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin', [ViewController::class, 'dashboard'])->name('index.admin');

    Route::get('/satuan', [ViewController::class, 'satuan'])->name('satuanBarang');
    Route::post('/satuan', [SatuanController::class, 'create'])->name('satuan.create');
    Route::delete('/satuan/{id}', [SatuanController::class, 'delete'])->name('satuan.delete');
    Route::post('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');

    Route::get('/barang', [ViewController::class, 'barang'])->name('barang');
    Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store');
    Route::post('barang/update/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'delete'])->name('barang.delete');

    Route::get('/kartu-stok', [ViewController::class, 'kartuStok'])->name('kartuStok');
    Route::post('kartu-stok/store', [KartuStokController::class, 'store'])->name('kartuStok.store');
    Route::get('/kartu-stok/{idkartu_stok}/edit', [KartuStokController::class, 'edit'])->name('kartuStok.edit');
    Route::put('/kartu-stok/{idkartu_stok}/update', [KartuStokController::class, 'update'])->name('kartuStok.update');
    Route::delete('/kartu-stok/{idkartu_stok}/delete', [KartuStokController::class, 'delete'])->name('kartuStok.delete');
    Route::get('/kartu-stok/history/{id}', [KartuStokController::class, 'getHistory']);
    Route::post('/search-kartu-stok', [KartuStokController::class, 'searchKartuStok'])->name('search.kartu.stok');

    Route::get('/addrole', [ViewController::class, 'addrole'])->name('addrole');
    Route::post('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/update/{id}', [RoleController::class, 'update'])->name('roles.update'); // Route untuk update
    Route::delete('/roles/delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');

    Route::get('/adduser', [ViewController::class, 'adduser'])->name('adduser');
    Route::post('/users/store', [UserController::class, 'create'])->name('users.store');
    Route::delete('/users/destroy/{id}', [UserController::class, 'delete'])->name('users.destroy');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');

    Route::get('/addvendor', [ViewController::class, 'addvendor'])->name('addvendor');
    Route::prefix('vendor')->group(function () {
        Route::post('/create', [VendorController::class, 'create'])->name('vendor.create');
        Route::post('/update/{id}', [VendorController::class, 'update'])->name('vendor.update');
        Route::delete('/delete/{id}', [VendorController::class, 'delete'])->name('vendor.delete');
    });

    Route::get('/pengadaan', [ViewController::class, 'dashboarduser'])->name('pengadaan');
    Route::get('/pengadaan/create', [PengadaanController::class, 'create'])->name('pengadaan.create');
    Route::post('/pengadaan/delete', [PengadaanController::class, 'delete'])->name('pengadaan.delete');
    Route::get('/pengadaan/caribarang', [PengadaanController::class, 'caribarang'])->name('pengadaan.caribarang');
    Route::post('/pengadaan/store', [PengadaanController::class, 'store'])->name('pengadaan.store');
    Route::post('/caribarang', [PengadaanController::class, 'caribarang'])->name('caribarang');
    Route::get('/pengadaan/detail/{id}', [PengadaanController::class, 'detail'])->name('pengadaan.detail');
    Route::get('/pengadaan/detailvalidasi/{id}', [PengadaanController::class, 'detailvalidasi'])->name('pengadaan.detailvalidasi');
    Route::post('/pengadaan/terima/{id}', [PengadaanController::class, 'terimaPengadaan']);

    Route::get('/penerimaan', [ViewController::class, 'penerimaan'])->name('penerimaan.index');
    Route::get('/penerimaan/comparison/{id}', [PenerimaanController::class, 'viewPenerimaanComparison'])->name('penerimaan.comparison');
    Route::get('/penerimaan/detail/{id}', [PenerimaanController::class, 'detailPenerimaan']);
    Route::get('/penerimaan/return-list/{idPengadaan}', [PenerimaanController::class, 'showReturnList'])->name('penerimaan.list');

    Route::post('/return-items', [ReturnController::class, 'return'])->name('returnItems');
    Route::post('/return-penerimaan', [ReturnController::class, 'returnPenerimaan']);

    Route::get('/penjualan', [ViewController::class, 'penjualan'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::get('/penjualan/detail/{id}', [PenjualanController::class, 'detail'])->name('penjualan.detail');
    Route::get('/penjualan/barang', [PenjualanController::class, 'getBarang'])->name('penjualan.getBarang');
    Route::post('/caribarang2', [PenjualanController::class, 'caribarang2'])->name('caribarang2');
    Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
    
    Route::get('/margin', [ViewController::class, 'margin'])->name('margin');
    Route::post('/margin-penjualan/store', [MarginController::class, 'store'])->name('margin.store');
    Route::post('/margin-penjualan/update/{id}', [MarginController::class, 'update'])->name('margin.update');
    Route::delete('/margin-penjualan/delete/{id}', [MarginController::class, 'delete'])->name('margin.delete');
    Route::get('/margins', [MarginController::class, 'getMargins'])->name('getMargins');
    
    Route::post('/logout', [AuthController::class, 'logoutakun'])->name('logout');
    
    Route::get('/return', [ViewController::class, 'return'])->name('return');
    Route::get('/return/detail/{id}', [ReturnController::class, 'detail'])->name('return.detail');
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
