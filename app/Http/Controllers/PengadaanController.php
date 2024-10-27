<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

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
    public function terimaPengadaan(Request $request, $idpengadaan)
    {
        $items = $request->input('items');

        DB::beginTransaction();

        try {
            // Mengecek ketersediaan stok
            foreach ($items as $item) {
                $detail = DB::selectOne(
                    'SELECT dp.jumlah, ks.stock
                FROM detail_pengadaan dp
                JOIN kartu_stok ks ON dp.idbarang = ks.idbarang
                WHERE dp.idpengadaan = ? AND dp.idbarang = ?
                ORDER BY ks.create_at DESC
                LIMIT 1',
                    [$idpengadaan, $item['idbarang']],
                );

                if ($item['quantity'] > $detail->stock) {
                    return response()->json(
                        [
                            'status' => 'error',
                            'message' => 'Stok tidak mencukupi untuk item: ' . $item['idbarang'],
                        ],
                        400,
                    );
                }
            }

            // Menambahkan data ke tabel `penerimaan` dan mendapatkan ID terakhir yang dimasukkan
            DB::insert('INSERT INTO penerimaan (created_at, status, idpengadaan, iduser) VALUES (?, ?, ?, ?)', [now(), 'A', $idpengadaan, auth()->user()->iduser]);

            // Mendapatkan `idpenerimaan` paling akhir berdasarkan waktu pembuatan
            $idpenerimaan = DB::selectOne('SELECT idpenerimaan FROM penerimaan ORDER BY created_at DESC LIMIT 1')->idpenerimaan;

            foreach ($items as $item) {
                // Mengambil harga satuan dari tabel `barang`
                $hargaSatuan = DB::selectOne(
                    'SELECT harga
                FROM barang
                WHERE idbarang = ?',
                    [$item['idbarang']],
                )->harga;

                $subTotal = $hargaSatuan * $item['quantity'];

                // Mendapatkan stok saat ini dari `kartu_stok`
                $currentStock = DB::selectOne(
                    'SELECT stock
                FROM kartu_stok
                WHERE idbarang = ?
                ORDER BY create_at DESC
                LIMIT 1',
                    [$item['idbarang']],
                )->stock;

                // Menambahkan data ke `detail_penerimaan`
                DB::insert(
                    'INSERT INTO detail_penerimaan (jumlah_terima, harga_satuan_terima, sub_total_terima, idpenerimaan, idbarang)
                VALUES (?, ?, ?, ?, ?)',
                    [$item['quantity'], $hargaSatuan, $subTotal, $idpenerimaan, $item['idbarang']],
                );

                // Menambahkan data ke `kartu_stok`
                DB::insert(
                    'INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, idbarang, create_at, idtransaksi)
                VALUES (?, ?, ?, ?, ?, ?, ?)',
                    ['K', 0, $item['quantity'], $currentStock - $item['quantity'], $item['idbarang'], now(), $idpengadaan],
                );
            }

            // Mengupdate status pengadaan di tabel `pengadaan`
            DB::update(
                'UPDATE pengadaan
            SET status = ?
            WHERE idpengadaan = ?',
                ['B', $idpengadaan],
            );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pengadaan berhasil diterima, stok diperbarui, dan status pengadaan diubah menjadi "B"',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
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
                'SELECT dp.iddetail_pengadaan, dp.idpengadaan, b.nama, dp.harga_satuan, dp.jumlah, dp.sub_total, p.status,p.idpengadaan
            FROM detail_pengadaan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            JOIN pengadaan p on p.idpengadaan = dp.idpengadaan
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
            $stoktrakhir = DB::select(
                'SELECT ks1.idbarang, ks1.stock
            FROM kartu_stok ks1
            JOIN (
                SELECT idbarang, MAX(create_at) AS last_create_at
                FROM kartu_stok
                GROUP BY idbarang
            ) ks2 ON ks1.idbarang = ks2.idbarang AND ks1.create_at = ks2.last_create_at;',
            );

            $detailPengadaan = DB::select(
                'SELECT
                p.idpengadaan, u.username, v.nama_vendor, p.subtotal_nilai, p.total_nilai, p.ppn, p.status, p.timestamp,
                dp.iddetail_pengadaan, b.nama AS nama_barang, dp.harga_satuan, dp.jumlah, dp.sub_total, s.nama_satuan,
                ks.stock AS latest_stock, b.idbarang
            FROM pengadaan p
            JOIN users u ON p.users_iduser = u.iduser
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            JOIN detail_pengadaan dp ON p.idpengadaan = dp.idpengadaan
            JOIN barang b ON dp.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN (
                SELECT idbarang, stock
                FROM kartu_stok
                WHERE create_at = (
                    SELECT MAX(create_at)
                    FROM kartu_stok ks2
                    WHERE ks2.idbarang = kartu_stok.idbarang
                )
            ) ks ON b.idbarang = ks.idbarang
            WHERE p.idpengadaan = ?',
                [$id],
            );

            if (empty($detailPengadaan)) {
                return response()->json(['message' => 'Data tidak ditemukan!'], 404);
            }

            return response()->json([
                'stoktrakhir' => $stoktrakhir,
                'detailPengadaan' => $detailPengadaan,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Terjadi kesalahan saat mengambil detail pengadaan.',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
