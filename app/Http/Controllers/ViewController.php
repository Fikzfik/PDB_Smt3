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

        // Join pengadaan, detail_pengadaan, barang, satuan, vendor, and users
        $detail = DB::select('SELECT p.idpengadaan, u.username, v.nama_vendor, p.subtotal_nilai, p.total_nilai, p.ppn, p.status, p.timestamp,
            dp.iddetail_pengadaan, b.nama, dp.harga_satuan, dp.jumlah, dp.sub_total, s.nama_satuan
            FROM pengadaan p
            JOIN users u ON p.users_iduser = u.iduser
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            JOIN detail_pengadaan dp ON p.idpengadaan = dp.idpengadaan
            JOIN barang b ON dp.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
    ');
        $pengadaans = DB::select('SELECT p.idpengadaan, u.username, v.nama_vendor, p.subtotal_nilai, p.total_nilai, p.ppn, p.status, p.timestamp
            FROM pengadaan p
            JOIN users u ON p.users_iduser = u.iduser
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            WHERE p.status = "A"
    ');

        // Count pending procurements
        $jumlahPending = DB::select('SELECT COUNT(*) as total FROM pengadaan WHERE status = ?', ['A']);
        $jumlahPending = $jumlahPending[0]->total;

        $jumlahSucces = DB::select('SELECT COUNT(*) as total FROM pengadaan WHERE status = ?', ['B']);
        $jumlahSucces = $jumlahSucces[0]->total;
        // Count total returns
        $jumlahReturn = DB::select('SELECT COUNT(*) as total FROM returr');
        $jumlahReturn = $jumlahReturn[0]->total;

        return view('dashboardadmin', compact('validUser', 'pengadaans', 'jumlahPending', 'jumlahReturn', 'detail','jumlahSucces'));
    }

    public function dashboarduser()
    {
        $validUser = Auth::user();
        $pengadaans = DB::select('SELECT p.idpengadaan, u.username, v.nama_vendor, p.subtotal_nilai, p.total_nilai, p.ppn, p.status
            FROM pengadaan p
            JOIN users u ON p.users_iduser = u.iduser
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
        ');
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
    public function kartustok()
    {
        $validUser = Auth::user();
        $barang = DB::select("SELECT barang.idbarang, barang.nama, ks.stok_terakhir
            FROM barang
            LEFT JOIN (
                SELECT idbarang, stock AS stok_terakhir
                FROM kartu_stok
                WHERE (idbarang, create_at) IN (
                    SELECT idbarang, MAX(create_at)
                    FROM kartu_stok
                    GROUP BY idbarang
                )
            ) AS ks ON barang.idbarang = ks.idbarang
        ");

        return view('kartustok.index', compact('validUser', 'barang'));
    }
    public function addvendor()
    {
        $validUser = Auth::user();
        $vendors = DB::select('SELECT * FROM vendor');
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
        $penerimaans = DB::select("SELECT p.idpengadaan,
           MAX(p.idpenerimaan) AS idpenerimaan,
           MAX(p.created_at) AS created_at,
           u.username,
           p.status
            FROM penerimaan p
            JOIN users u ON p.iduser = u.iduser
            GROUP BY p.idpengadaan, u.username, p.status
        ");

        return view('penerimaan.index', compact('validUser', 'penerimaans'));
    }
}
