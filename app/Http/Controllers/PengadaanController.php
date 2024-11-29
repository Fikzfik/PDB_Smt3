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
        $barangPilih = json_decode($request->input('barangPilih'), true); // Decode JSON

        try {
            // Mulai transaksi
            DB::beginTransaction();

            // Panggil fungsi MySQL untuk menghitung PPN
            $ppn = DB::selectOne('SELECT CalculatePPN(?) AS ppn', [$subtotal])->ppn;

            // Hitung total
            $total = $subtotal + $ppn;

            // Simpan data pengadaan menggunakan prosedur
            $pengadaanId = DB::selectOne('CALL InsertPengadaan(?, ?, ?, ?, ?, ?, @p_idpengadaan)', [
                auth()->user()->iduser, // The ID of the logged-in user
                $idVendor, // The ID of the vendor
                'A', // Status (e.g., 'A' for active)
                $subtotal, // Subtotal value
                $ppn, // PPN value
                $total, // Total value
            ]);

            // Ambil ID pengadaan dari variabel output
            $pengadaanId = DB::selectOne('SELECT @p_idpengadaan AS idpengadaan')->idpengadaan;

            // Simpan detail pengadaan
            foreach ($barangPilih as $barang) {
                DB::statement('CALL InsertDetailPengadaan(?, ?, ?, ?, ?)', [$pengadaanId, $barang['id_barang'], $barang['harga'], $barang['quantity'], $barang['subtotal']]);
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

        if (empty($items)) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada item yang dipilih.'], 400);
        }

        DB::beginTransaction();

        try {
            // Tambahkan data penerimaan ke tabel penerimaan
            DB::statement('CALL InsertPenerimaan(?, ?, ?, ?, @idpenerimaan)', [
                now(), // Waktu penerimaan
                'A', // Status default
                $idpengadaan, // ID pengadaan terkait
                auth()->user()->iduser, // User yang menerima
            ]);

            // Ambil ID penerimaan yang dihasilkan
            $idpenerimaan = DB::selectOne('SELECT @idpenerimaan AS idpenerimaan')->idpenerimaan;

            foreach ($items as $item) {
                $idbarang = $item['idbarang'];
                $jumlah = $item['quantity'];

                // Validasi jumlah barang terhadap pengadaan
                $detailPengadaan = DB::selectOne(
                    'SELECT (dp.jumlah - COALESCE(SUM(dr.jumlah_terima), 0)) AS sisa_jumlah
        FROM detail_pengadaan dp
        LEFT JOIN barang b ON b.idbarang = dp.idbarang
        LEFT JOIN detail_penerimaan dr ON dp.idbarang = dr.idbarang
        WHERE dp.idpengadaan = ? AND dp.idbarang = ?
        GROUP BY dp.iddetail_pengadaan, dp.jumlah',
                    [$idpengadaan, $idbarang],
                );

                if (!$detailPengadaan || $detailPengadaan->sisa_jumlah <= 0) {
                    throw new \Exception("Barang dengan ID {$idbarang} tidak tersedia untuk diterima.");
                }

                if ($jumlah > $detailPengadaan->sisa_jumlah) {
                    throw new \Exception("Jumlah penerimaan untuk barang ID {$idbarang} melebihi permintaan pengadaan. Sisa jumlah: {$detailPengadaan->sisa_jumlah}.");
                }

                // Validasi barang dan harga satuan
                $barang = DB::selectOne('SELECT harga FROM barang WHERE idbarang = ?', [$idbarang]);
                if (!$barang) {
                    throw new \Exception("Barang dengan ID {$idbarang} tidak ditemukan.");
                }

                $hargaSatuan = $barang->harga;
                $subTotal = $hargaSatuan * $jumlah;

                // Tambahkan data detail penerimaan
                DB::insert(
                    'INSERT INTO detail_penerimaan (jumlah_terima, harga_satuan_terima, sub_total_terima, idpenerimaan, idbarang)
        VALUES (?, ?, ?, ?, ?)',
                    [$jumlah, $hargaSatuan, $subTotal, $idpenerimaan, $idbarang],
                );
            }

            $isAllZero = DB::selectOne('SELECT isAllItemsReceived(?) AS is_all_received', [$idpengadaan])->is_all_received;

            if ($isAllZero) {
                // Update status pengadaan jika semua barang telah diterima
                DB::update('UPDATE pengadaan SET status = ? WHERE idpengadaan = ?', ['B', $idpengadaan]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pengadaan berhasil diterima.' . ($isAllZero ? ' Semua detail terpenuhi.' : ''),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function detail($id)
    {
        try {
            // Mengambil detail pengadaan dengan stok yang dibutuhkan
            $detailPengadaan = DB::select('CALL getDetailPengadaan(?)', [$id]);

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
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function detailvalidasi($id)
    {
        try {
            $stokTerakhir = DB::select('CALL getStokTerakhir()');

            $detailPengadaan = DB::select('CALL getDetailPengadaan(?)', [$id]);

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
