<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DetailPengadaan;
use App\Models\ViewPengadaan;
use App\Models\ViewPenerimaan;
use App\Models\ViewPenjualan;
use App\Models\ViewVendor;
use App\Models\ViewPengadaanWhereA;
use App\Models\KartuStockBarang;

class ViewController extends Controller
{
    public function dashboard()
    {
        $validUser = Auth::user();

        // Join pengadaan, detail_pengadaan, barang, satuan, vendor, and users
        $detail = DetailPengadaan::all();
        $pengadaans = ViewPengadaanWhereA::all();
        
        // Count pending procurements
        $jumlahPending = DB::select('SELECT COUNT(*) as total FROM pengadaan WHERE status = ?', ['A']);
        $jumlahPending = $jumlahPending[0]->total;

        $jumlahSucces = DB::select('SELECT COUNT(*) as total FROM pengadaan WHERE status = ?', ['B']);
        $jumlahSucces = $jumlahSucces[0]->total;
        // Count total returns
        $jumlahReturn = DB::select('SELECT COUNT(*) as total FROM returr');
        $jumlahReturn = $jumlahReturn[0]->total;

        return view('dashboardadmin', compact('validUser', 'pengadaans', 'jumlahPending', 'jumlahReturn', 'detail', 'jumlahSucces'));
    }

    public function dashboarduser()
    {
        $validUser = Auth::user();
        $pengadaans = ViewPengadaan::all();
        // @dd($pengadaans);
        return view('dashboarduser', compact('validUser', 'pengadaans'));
    }
    public function login()
    {
        $validUser = Auth::user();
        return view('auth.login', compact('validUser'));
    }
    public function register()
    {
        $validUser = Auth::user();
        return view('auth.register', compact('validUser'));
    }
    public function satuan()
    {
        $validUser = Auth::user();
        $satuan = DB::select('SELECT * FROM Satuan');
        return view('satuan.index', compact('validUser', 'satuan'));
    }
    public function barang()
    {
        $validUser = Auth::user();
        $barang = DB::select('SELECT * FROM Barang JOIN Satuan ON Barang.idsatuan = Satuan.idsatuan');
        $satuan = DB::select('SELECT * FROM Satuan');
        // @dd($barang);
        return view('barang.index', compact('validUser', 'barang', 'satuan'));
    }
    public function margin()
    {
        $validUser = Auth::user();
        $margin = DB::select('SELECT * FROM margin_penjualan');
        return view('margin.index', compact('validUser', 'margin'));
    }
    public function kartustok()
    {
        $validUser = Auth::user();
        $barang = KartuStockBarang::all();

        return view('kartustok.index', compact('validUser', 'barang'));
    }
    public function addvendor()
    {
        $validUser = Auth::user();
        $vendors = ViewVendor::all();
        return view('vendor.index', compact('validUser', 'vendors'));
    }
    public function addrole()
    {
        $validUser = Auth::user();
        $barang = DB::select('SELECT * FROM Barang');
        $roles = DB::select('SELECT * FROM role');
        return view('addrole.index', compact('roles'));
    }
    public function adduser()
    {
        $validUser = Auth::user();
        $roles = DB::select('SELECT * FROM role');
        $users = DB::select('SELECT * FROM users');
        return view('user.index', compact('roles', 'users'));
    }
    public function test()
    {
        return view('test');
    }
    public function penerimaan()
    {
        $validUser = Auth::user();
        $penerimaans = ViewPenerimaan::all();

        return view('penerimaan.index', compact('validUser', 'penerimaans'));
    }
    public function penjualan()
    {
        $validUser = Auth::user();
        $penjualans = ViewPenjualan::all();

        return view('penjualan.index', compact('validUser', 'penjualans'));
    }
}
