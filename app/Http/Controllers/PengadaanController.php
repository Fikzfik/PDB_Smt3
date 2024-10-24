<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PengadaanController extends Controller
{
    public function create()
    {
        $validUser = Auth::user();
        $pengadaans = DB::select('SELECT * FROM pengadaan');
        $vendors = DB::select('SELECT * FROM vendor');
        // @dd($pengadaans, $vendors, $validUser);
        return view('pengadaan.create', compact('validUser', 'pengadaans', 'vendors'));
    }
    public function caribarang(Request $request)
    {
        try {
            // Raw SQL untuk pencarian barang
            $barang = DB::select(
                "SELECT b.idbarang, b.nama, b.harga, s.nama_satuan
                          FROM barang b
                          JOIN satuan s ON b.idsatuan = s.idsatuan
                          WHERE b.nama LIKE ?",
                ['%' . $request->barang . '%'],
            );

            return response()->json($barang);
        } catch (\Exception $e) {
            // Jika ada error, tampilkan pesan error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_vendor' => 'required|integer',
            'subtotal' => 'required|numeric',
            'barangPilih' => 'required|json',
        ]);

        // Ambil data dari request
        $idVendor = $request->input('id_vendor');
        $subtotal = $request->input('subtotal');
        $ppn = (int) ($subtotal * 0.11); // Hitung PPN 11%
        $total = $subtotal + $ppn; // Total = Subtotal + PPN
        $barangPilih = json_decode($request->input('barangPilih'), true); // Decode JSON

        try {
            // Mulai transaksi
            DB::beginTransaction();

            // Simpan data pengadaan
            $pengadaanId = DB::insert('INSERT INTO pengadaan (timestamp, users_iduser, vendor_idvendor, status, subtotal_nilai, ppn, total_nilai) VALUES (NOW(), ?, ?, ?, ?, ?, ?)', [
                auth()->user()->iduser, // ID user yang sedang login
                $idVendor,
                'A', // Status (misalnya 'A' untuk aktif)
                $subtotal,
                $ppn,
                $total,
            ]);

            // Ambil ID pengadaan yang baru saja disimpan
            $pengadaanId = DB::getPdo()->lastInsertId();

            // Simpan detail pengadaan
            foreach ($barangPilih as $barang) {
                DB::insert('INSERT INTO detail_pengadaan (idpengadaan, idbarang, harga_satuan, jumlah, sub_total) VALUES (?, ?, ?, ?, ?)', [$pengadaanId, $barang['id_barang'], $barang['harga'], $barang['quantity'], $barang['subtotal']]);
            }

            // Commit transaksi
            DB::commit();

            // Kembalikan respon sukses
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            // Jika terjadi error, rollback transaksi
            DB::rollBack();

            // Kembalikan respon error
            return response()->json(
                [
                    'message' => 'error',
                    'data' => $request->all(),
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }
    public function detail($id)
    {
        try {
            // Mengambil detail pengadaan dengan raw SQL
            $detailPengadaan = DB::select(
                'SELECT dp.iddetail_pengadaan, dp.idpengadaan, b.nama, dp.harga_satuan, dp.jumlah, dp.sub_total
            FROM detail_pengadaan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            WHERE dp.idpengadaan = ?',
                [$id],
            );

            // Memeriksa apakah ada data yang ditemukan
            if (empty($detailPengadaan)) {
                return response()->json(['message' => 'Data tidak ditemukan!'], 404);
            }

            // Mengembalikan data detail pengadaan sebagai JSON
            return response()->json($detailPengadaan);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Terjadi kesalahan saat mengambil detail pengadaan.',
                    'error' => $e->getMessage(), // Menyertakan pesan kesalahan
                ],
                500,
            );
        }
    }
    public function detailvalidasi($id)
    {
        try {
            // Mengambil detail pengadaan berdasarkan ID pengadaan yang diberikan
            $detailPengadaan = DB::select(
                'SELECT p.idpengadaan, u.username, v.nama_vendor, p.subtotal_nilai, p.total_nilai, p.ppn, p.status, p.timestamp,
                   dp.iddetail_pengadaan, b.nama, dp.harga_satuan, dp.jumlah, dp.sub_total, s.nama_satuan
            FROM pengadaan p
            JOIN users u ON p.users_iduser = u.iduser
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            JOIN detail_pengadaan dp ON p.idpengadaan = dp.idpengadaan
            JOIN barang b ON dp.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE p.idpengadaan = ?',
                [$id],
            ); // Kondisi untuk mengambil data berdasarkan ID pengadaan

            // Memeriksa apakah ada data yang ditemukan
            if (empty($detailPengadaan)) {
                return response()->json(['message' => 'Data tidak ditemukan!'], 404);
            }

            // Mengembalikan data detail pengadaan sebagai JSON
            return response()->json($detailPengadaan);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Terjadi kesalahan saat mengambil detail pengadaan.',
                    'error' => $e->getMessage(), // Menyertakan pesan kesalahan
                ],
                500,
            );
        }
    }
}
