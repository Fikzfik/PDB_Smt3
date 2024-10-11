<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class KartuStokController extends Controller
{
    public function searchKartuStok(Request $request)
    {
        $search = $request->input('search');

        $kartu_stok = DB::select(
            'SELECT kartu_stok.*, barang.nama
         FROM kartu_stok
         JOIN barang ON barang.idbarang = kartu_stok.idbarang
         WHERE barang.nama LIKE :search',
            ['search' => "%$search%"],
        );

        return response()->json($kartu_stok);
    }

    public function store(Request $request)
    {
        $stokTerakhir = DB::select(
            'SELECT kartu_stok.stock
        FROM kartu_stok
        JOIN barang ON kartu_stok.idbarang = barang.idbarang
        WHERE kartu_stok.idbarang = ?
        ORDER BY kartu_stok.idkartu_stok DESC
        LIMIT 1',
            [$request->idbarang],
        );
        if (!empty($stokTerakhir)) {
            $stokTerakhir = $stokTerakhir[0]->stock;
        } else {
            $stokTerakhir = 0;
        }
        if ($request->jenis_transaksi == 'M') {
            DB::insert(
                'INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, idbarang, create_at, idtransaksi)
            VALUES (?, ?, ?, ?, ?, ?, ?)',
                [$request->jenis_transaksi, $request->jumlah, 0, $stokTerakhir + $request->jumlah, $request->idbarang, now(), null],
            );
        } else {
            if ($stokTerakhir - $request->jumlah < 0) {
                return redirect()->back()->with('error', 'Stock Tidak Mencukupi');
            } else {
                DB::insert(
                    'INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, idbarang, create_at, idtransaksi)
                VALUES (?, ?, ?, ?, ?, ?, ?)',
                    [$request->jenis_transaksi, 0, $request->jumlah, $stokTerakhir - $request->jumlah, $request->idbarang, now(), null],
                );
            }
        }
        return redirect()->back()->with('success', 'Kartu stok berhasil disimpan!');
    }
    // Menampilkan form edit data kartu stok
    public function edit($idkartu_stok)
    {
        // Mengambil data kartu stok berdasarkan id menggunakan raw SQL
        $kartuStok = DB::select('SELECT * FROM kartu_stok WHERE idkartu_stok = ?', [$idkartu_stok]);

        // Cek jika data ditemukan
        if (empty($kartuStok)) {
            return redirect()->back()->with('error', 'Data not found');
        }

        // Mengambil data barang untuk keperluan pilihan pada form
        $barang = DB::select('SELECT * FROM barang');

        return view('kartu_stok.edit', ['kartuStok' => $kartuStok[0], 'barang' => $barang]);
    }

    // Memperbarui data kartu stok
    public function update(Request $request, $idkartu_stok)
    {
        // Validasi input
        $request->validate([
            'jenis_transaksi' => 'required',
            'masuk' => 'required|integer',
            'keluar' => 'required|integer',
            'idbarang' => 'required|integer',
        ]);

        // Menggunakan raw SQL untuk update data kartu stok
        DB::update('UPDATE kartu_stok SET jenis_transaksi = ?, masuk = ?, keluar = ?, idbarang = ? WHERE idkartu_stok = ?', [$request->jenis_transaksi, $request->masuk, $request->keluar, $request->idbarang, $idkartu_stok]);

        return redirect()->route('kartu-stok.index')->with('success', 'Data updated successfully');
    }
    public function history($id)
    {
        $history = DB::select('SELECT * FROM kartu_stok WHERE idbarang = ? ORDER BY created_at DESC', [$id]);

        if ($history) {
            return response()->json([
                'status' => 'success',
                'data' => $history,
            ]);
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Tidak ada history untuk Barang ini.',
                ],
                404,
            );
        }
    }
}
