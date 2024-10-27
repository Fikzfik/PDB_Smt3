<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class ReturnController extends Controller
{
    public function return(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        $idPenerimaan = $request->input('idpenerimaan');

        DB::beginTransaction();

        try {
            if (!$selectedItems) {
                return response()->json(['status' => 'error', 'message' => 'No items selected for return.']);
            }

            // Periksa apakah `idpenerimaan` ada di tabel `penerimaan`
            $penerimaanExists = DB::selectOne('SELECT COUNT(*) as count FROM penerimaan WHERE idpenerimaan = ?', [$idPenerimaan]);

            if ($penerimaanExists->count == 0) {
                return response()->json(['status' => 'error', 'message' => 'Invalid penerimaan ID.']);
            }

            // Tambahkan data ke tabel `returr` dan dapatkan ID retur yang baru dimasukkan
            DB::insert('INSERT INTO returr (idpenerimaan, iduser, created_at) VALUES (?, ?, ?)', [$idPenerimaan, auth()->id(), now()]);
            $returId = DB::selectOne('SELECT LAST_INSERT_ID() as id')->id;

            foreach ($selectedItems as $itemId) {
                // Ambil detail penerimaan untuk item yang dikembalikan
                $detailPenerimaan = DB::selectOne(
                    'SELECT idbarang, jumlah_terima
                FROM detail_penerimaan
                WHERE iddetail_penerimaan = ?',
                    [$itemId],
                );

                if ($detailPenerimaan) {
                    // Dapatkan stok saat ini dari `kartu_stok`
                    $currentStock = DB::selectOne(
                        'SELECT stock
                    FROM kartu_stok
                    WHERE idbarang = ?
                    ORDER BY create_at DESC
                    LIMIT 1',
                        [$detailPenerimaan->idbarang],
                    )->stock;

                    // Tambahkan data ke `kartu_stok` untuk menambah stok kembali (jenis transaksi "M")
                    DB::insert(
                        'INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, create_at, idtransaksi, idbarang)
                    VALUES (?, ?, ?, ?, ?, ?, ?)',
                        ['M', $detailPenerimaan->jumlah_terima, 0, $currentStock + $detailPenerimaan->jumlah_terima, now(), $returId, $detailPenerimaan->idbarang],
                    );

                    // Tambahkan data ke `detail_retur`
                    DB::insert(
                        'INSERT INTO detail_retur (jumlah, alasan, idretur, iddetail_penerimaan)
                    VALUES (?, ?, ?, ?)',
                        [$detailPenerimaan->jumlah_terima, $request->input('alasan')[$itemId] ?? 'No reason provided', $returId, $itemId],
                    );

                    // Hapus data di `detail_retur` yang terkait dengan `iddetail_penerimaan`
                    DB::delete('DELETE FROM detail_retur WHERE iddetail_penerimaan = ?', [$itemId]);

                    // Hapus `detail_penerimaan` yang sudah di-retur
                    DB::delete('DELETE FROM detail_penerimaan WHERE iddetail_penerimaan = ?', [$itemId]);
                }
            }

            // Periksa apakah masih ada item di `detail_penerimaan` untuk penerimaan ini
            $remainingDetails = DB::selectOne(
                'SELECT COUNT(*) as count
            FROM detail_penerimaan
            WHERE idpenerimaan = ?',
                [$idPenerimaan],
            );

            // Jika tidak ada item yang tersisa, ubah status `penerimaan` menjadi "B"
            if ($remainingDetails->count == 0) {
                DB::update(
                    'UPDATE penerimaan
                SET status = "B"
                WHERE idpenerimaan = ?',
                    [$idPenerimaan],
                );
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Items successfully returned, stock updated, and status updated if no items remain.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
