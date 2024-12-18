<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function detail($id)
    {
        $details = DB::select(
            '
            SELECT dp.iddetail_penjualan, dp.idpenjualan, b.nama, dp.harga_satuan, dp.jumlah, dp.subtotal
            FROM detail_penjualan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            WHERE dp.idpenjualan = ?
        ',
            [$id],
        );

        return response()->json($details);
    }
    public function create()
    {
        $margins = DB::select('SELECT * FROM margin_penjualan');

        $validUser = Auth::user();
        return view('penjualan.create', compact('validUser', 'margins'));
    }
    public function caribarang2(Request $request)
    {
        try {
            // Raw SQL untuk pencarian barang yang memiliki stok, dengan GROUP BY
            $barang = DB::select(
                "SELECT b.idbarang, b.nama, b.harga, s.nama_satuan, ks.stok_terakhir AS stock
                FROM barang b
                JOIN satuan s ON b.idsatuan = s.idsatuan
                LEFT JOIN (
                    SELECT ks.idbarang, ks.stock AS stok_terakhir
                    FROM kartu_stok ks
                    WHERE (ks.idbarang, ks.create_at) IN (
                        SELECT k.idbarang, MAX(k.create_at)
                        FROM kartu_stok k
                        GROUP BY k.idbarang
                    )
                ) ks ON b.idbarang = ks.idbarang
                WHERE b.nama LIKE ? AND ks.stok_terakhir > 0",
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
        // Ambil data dari request
        $idMarginPenjualan = $request->input('idmargin_penjualan');
        $subtotal = $request->input('total'); // Total subtotal
        $margin = $request->input('margin'); // Margin persentase
        $marginValue = $request->input('marginValue'); // Nilai margin
        $total = $subtotal + $marginValue; // Total = Subtotal + Margin Value
        $barangPilih = $request->input('barang');

        try {
            // Mulai transaksi
            DB::beginTransaction();

            // Simpan data penjualan
            DB::insert(
                'INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, iduser, idmargin_penjualan)
            VALUES (NOW(), ?, ?, ?, ?, ?)',
                [
                    $subtotal,
                    $marginValue, // Nilai margin (bukan PPN)
                    $total,
                    auth()->user()->iduser, // ID user yang sedang login
                    $idMarginPenjualan, // ID margin penjualan
                ],
            );

            // Ambil ID penjualan yang baru saja disimpan
            $penjualanId = DB::getPdo()->lastInsertId();

            // Simpan detail penjualan untuk setiap barang
            foreach ($barangPilih as $barang) {
                // Simpan detail penjualan
                DB::insert(
                    'INSERT INTO detail_penjualan (idpenjualan, idbarang, harga_satuan, jumlah, subtotal)
                     VALUES (?, ?, ?, ?, ?)',
                    [
                        $penjualanId, // ID penjualan
                        $barang['id_barang'], // ID barang
                        $barang['harga'], // Harga barang
                        $barang['quantity'], // Jumlah barang
                        $barang['subtotal'], // Subtotal barang
                    ],
                );

                // Ambil stok saat ini dari kartu stok berdasarkan idbarang
                $currentStockQuery = DB::selectOne('SELECT stock FROM kartu_stok WHERE idbarang = ? ORDER BY create_at DESC LIMIT 1', [$barang['id_barang']]);
                $currentStock = $currentStockQuery->stock ?? 0;

                // Hitung stok baru setelah pengurangan
                $newStock = $currentStock - $barang['quantity'];

                // Update stok di kartu stok
                DB::insert(
                    'INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, idbarang, create_at, idtransaksi)
                     VALUES (?, ?, ?, ?, ?, ?, ?)',
                    [
                        '1', // jenis_transaksi untuk penjualan
                        0, // masuk = 0 karena tidak ada penambahan stok
                        $barang['quantity'], // keluar = jumlah barang yang dijual
                        $newStock, // stok terbaru
                        $barang['id_barang'], // idbarang
                        now(), // create_at
                        $penjualanId, // idtransaksi terkait penjualan
                    ],
                );
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
    public function delete(Request $request){
        
    }
}
