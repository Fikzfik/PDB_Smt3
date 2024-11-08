<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;

class ReturnController extends Controller
{
    public function returnPenerimaan(Request $request)
    {
        $itemsData = $request->input('items');

        if (empty($itemsData)) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak lengkap atau kosong.'], 400);
        }

        DB::beginTransaction();

        try {
            // Log untuk memastikan itemsData berisi data yang diharapkan
            Log::info('ItemsData:', ['items' => $itemsData]);

            // Kelompokkan barang berdasarkan `idPenerimaan`
            $itemsGroupedByPenerimaan = collect($itemsData)->groupBy('idPenerimaan');

            foreach ($itemsGroupedByPenerimaan as $idPenerimaan => $items) {
                // Validasi apakah `idPenerimaan` valid
                if (empty($idPenerimaan)) {
                    throw new \Exception('ID penerimaan tidak valid atau kosong untuk salah satu item.');
                }

                // Buat record retur baru untuk setiap kelompok `idPenerimaan`
                DB::insert('INSERT INTO returr (created_at, idpenerimaan, iduser) VALUES (?, ?, ?)', [now(), $idPenerimaan, auth()->user()->iduser]);

                // Ambil `idretur` yang baru saja dibuat
                $idRetur = DB::getPdo()->lastInsertId();

                foreach ($items as $item) {
                    $idDetailPenerimaan = $item['idDetailPenerimaan'];
                    $jumlahReturn = (int) $item['jumlahReturn'];
                    $alasan = $item['alasan'];

                    // Validasi `detail_penerimaan`
                    $detailPenerimaan = DB::selectOne('SELECT * FROM detail_penerimaan WHERE iddetail_penerimaan = ?', [$idDetailPenerimaan]);

                    if (!$detailPenerimaan) {
                        throw new \Exception('Detail penerimaan tidak ditemukan untuk ID: ' . $idDetailPenerimaan);
                    }

                    // Validasi apakah jumlah return tidak melebihi jumlah yang diterima
                    if ($jumlahReturn > $detailPenerimaan->jumlah_terima) {
                        return response()->json(['status' => 'error', 'message' => 'Jumlah return melebihi jumlah yang diterima.'], 400);
                    }

                    // Masukkan data ke tabel `detail_retur`
                    DB::insert('INSERT INTO detail_retur (jumlah, alasan, idretur, iddetail_penerimaan) VALUES (?, ?, ?, ?)', [$jumlahReturn, $alasan, $idRetur, $idDetailPenerimaan]);

                    // Ambil stok saat ini dari `kartu_stok`
                    $currentStockQuery = DB::selectOne('SELECT stock FROM kartu_stok WHERE idbarang = ? ORDER BY create_at DESC LIMIT 1', [$detailPenerimaan->idbarang]);
                    $currentStock = $currentStockQuery->stock ?? 0;

                    // Perbarui stok di `kartu_stok`
                    DB::insert('INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, idbarang, create_at, idtransaksi) VALUES (?, ?, ?, ?, ?, ?, ?)', ['2', 0, $jumlahReturn, $currentStock - $jumlahReturn, $detailPenerimaan->idbarang, now(), $idRetur]);
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Barang berhasil dikembalikan dan stok diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Terjadi kesalahan saat memproses retur:', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
