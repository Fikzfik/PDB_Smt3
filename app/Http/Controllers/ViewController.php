<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ViewController extends Controller
{
    public function dashboard()
    {
        $validUser = Auth::user();
        return view('dashboardadmin', compact('validUser'));
    }
    public function dashboarduser()
    {
        $validUser = Auth::user();
        return view('dashboarduser', compact('validUser'));
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
    public function kartustok()
    {
        $validUser = Auth::user();
        $barang = DB::select('SELECT * FROM Barang');
        $kartu_stok = DB::select('SELECT * FROM kartu_stok JOIN barang ON Barang.idbarang = kartu_stok.idbarang');
        return view('kartustok.index', compact('validUser', 'barang', 'kartu_stok'));
    }
    public function addvendor()
    {
        $validUser = Auth::user();
        $vendors = DB::select('SELECT * FROM vendor');
        return view('vendor.index',compact('validUser','vendors'));
    }
    public function addrole()
    {
        $validUser = Auth::user();
        $barang = DB::select('SELECT * FROM Barang');
        $roles = DB::select('SELECT * FROM role');
        return view('addrole.index',compact('roles'));
    }
    public function adduser()
    {
        $validUser = Auth::user();
        $roles = DB::select('SELECT * FROM role');
        $users = DB::select('SELECT * FROM users');
        return view('user.index',compact('roles','users'));
    }
    public function test()
    {
        return view('test');
    }
}
