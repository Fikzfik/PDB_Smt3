<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        try {
            // Ambil stok terakhir dari tabel `kartu_stok` berdasarkan `idbarang`
            $stokTerakhir = DB::select('SELECT stock FROM kartu_stok WHERE idbarang = ? ORDER BY idkartu_stok DESC LIMIT 1', [$request->idbarang]);

            // Tentukan stok terakhir, jika tidak ada maka dianggap 0
            $stokTerakhir = $stokTerakhir ? $stokTerakhir[0]->stock : 0;

            // Hapus leading zeros dari jumlah
            $jumlah = (int) ltrim($request->jumlah, '0');

            // Hitung stok baru berdasarkan jenis transaksi
            $newStock = $request->jenis_transaksi == 'M' ? $stokTerakhir + $jumlah : $stokTerakhir - $jumlah;

            // Cek jika stok tidak cukup atau akan menjadi negatif saat transaksi keluar
            if ($request->jenis_transaksi == 'K' && $newStock < 0) {
                return response()->json(
                    [
                        'success' => false,
                        'error' => 'Stock Tidak Mencukupi',
                    ],
                    400,
                );
            }

            // Simpan data transaksi kartu stok baru
            DB::insert(
                'INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, idbarang, create_at, idtransaksi)
            VALUES (?, ?, ?, ?, ?, ?, ?)',
                [$request->jenis_transaksi, $request->jenis_transaksi == 'M' ? $jumlah : 0, $request->jenis_transaksi == 'K' ? $jumlah : 0, $newStock, $request->idbarang, Carbon::now(), null],
            );

            // Kembalikan JSON respons sukses dengan data terbaru
            return response()->json([
                'success' => true,
                'data' => [
                    'create_at' => now()->toDateTimeString(),
                    'jenis_transaksi' => $request->jenis_transaksi,
                    'jumlah' => $jumlah,
                    'stock' => $newStock,
                ],
            ]);
        } catch (Exception $e) {
            // Kembalikan respons JSON error dengan pesan dari exception
            return response()->json(
                [
                    'success' => false,
                    'error' => 'Terjadi kesalahan saat menyimpan data.',
                    'message' => $e->getMessage(), // untuk debugging
                    'line' => $e->getLine(), // untuk debugging
                ],
                500,
            );
        }
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
    public function getHistory($id)
    {
        $history = DB::select(
            "SELECT create_at,
               jenis_transaksi,
               stock
        FROM kartu_stok
        WHERE idbarang = :id
    ",
            ['id' => $id],
        );

        return response()->json(['data' => $history]);
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
