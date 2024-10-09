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
        return view('dashboard', compact('validUser'));
    }
    public function login()
    {
        $validUser = Auth::user();
        return view('login', compact('validUser'));
    }
    public function register()
    {
        $validUser = Auth::user();
        return view('register', compact('validUser'));
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
        $barang = DB::select(
            'SELECT * FROM Barang JOIN Satuan ON Barang.idsatuan = Satuan.idsatuan',
        );
        $satuan = DB::select('SELECT * FROM Satuan');
        // @dd($barang);
        return view('barang.index', compact('validUser', 'barang', 'satuan'));
    }
    public function kartustok()
    {
        $validUser = Auth::user();
        $barang = DB::select('SELECT * FROM Barang');
        $kartu_stok = DB::select(
            'SELECT * FROM kartu_stok JOIN barang ON Barang.idbarang = kartu_stok.idbarang',
        );
        return view('kartustok.index', compact('validUser', 'barang','kartu_stok'));
    }
    public function vendor()
    {
        
        return view('kartustok.index');
    }
    public function role()
    {
      
        return view('role');
    }
}
