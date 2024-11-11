<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PenerimaanController extends Controller
{
    public function detailPenerimaan($idPengadaan)
    {
        try {
            $detailPenerimaan = DB::select(
                'SELECT dp.iddetail_penerimaan, b.nama AS nama_barang, dp.harga_satuan_terima, dp.jumlah_terima, dp.sub_total_terima, p.created_at AS tanggal_penerimaan
             FROM detail_penerimaan dp
             JOIN barang b ON dp.idbarang = b.idbarang
             JOIN penerimaan p ON dp.idpenerimaan = p.idpenerimaan
             WHERE p.idpengadaan = ?',
                [$idPengadaan],
            );

            return response()->json($detailPenerimaan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function viewPenerimaanComparison($id)
    {
        try {
            // Mengambil detail pengadaan
            $pengadaanDetails = DB::select(
                'SELECT dp.iddetail_pengadaan, dp.idpengadaan, b.nama AS barang, dp.harga_satuan, dp.jumlah, dp.sub_total
            FROM detail_pengadaan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            WHERE dp.idpengadaan = ?',
                [$id],
            );

            // Mengambil detail penerimaan berdasarkan idpengadaan
            $penerimaanDetails = DB::select(
                'SELECT pr.idpenerimaan, pr.idpengadaan, b.nama AS barang,
            dp.iddetail_penerimaan, dp.jumlah_terima AS jumlah_diterima, dp.sub_total_terima
            FROM penerimaan pr
            JOIN detail_penerimaan dp ON dp.idpenerimaan = pr.idpenerimaan
            JOIN barang b ON b.idbarang = dp.idbarang
            WHERE pr.idpengadaan = ?',
                [$id],
            );

            // Mengambil idpenerimaan berdasarkan idpengadaan
            $idPenerimaan = DB::selectOne('SELECT idpenerimaan FROM penerimaan WHERE idpengadaan = ? ORDER BY idpenerimaan DESC LIMIT 1', [$id]);

            // Check if data exists
            if (empty($pengadaanDetails)) {
                return redirect()->route('index.user')->with('error', 'Data pengadaan tidak ditemukan.');
            }

            return view('penerimaan.compare', compact('pengadaanDetails', 'penerimaanDetails', 'idPenerimaan'));
        } catch (\Exception $e) {
            return redirect()
                ->route('index.user')
                ->with('error', 'Error fetching data: ' . $e->getMessage());
        }
    }
    public function showReturnList($idPengadaan)
{
    // Mengambil semua penerimaan berdasarkan idPengadaan
    $penerimaanList = DB::select(
        "SELECT p.idpenerimaan, p.created_at AS tanggal_penerimaan
        FROM penerimaan p
        WHERE p.idpengadaan = ?",
        [$idPengadaan]
    );

    // Mengambil detail penerimaan untuk setiap penerimaan
    $detailPenerimaan = [];
    foreach ($penerimaanList as $penerimaan) {
        // Ambil detail penerimaan untuk setiap penerimaan
        $detailPenerimaan[$penerimaan->idpenerimaan] = DB::select(
            "SELECT pd.iddetail_penerimaan, b.nama, pd.jumlah_terima,
            pd.harga_satuan_terima, pd.sub_total_terima
            FROM detail_penerimaan pd
            JOIN barang b ON b.idbarang = pd.idbarang
            WHERE pd.idpenerimaan = ?",
            [$penerimaan->idpenerimaan]
        );

        // Untuk setiap detail penerimaan, kita ambil detail retur
        foreach ($detailPenerimaan[$penerimaan->idpenerimaan] as $key => $detail) {
            $returnDetails = DB::select(
                "SELECT SUM(dr.jumlah) AS total_return
                FROM detail_retur dr
                WHERE dr.iddetail_penerimaan = ?",
                [$detail->iddetail_penerimaan]
            );

            // Jika ada retur, kurangi sub_total_terima dengan total_return
            $totalReturn = $returnDetails[0]->total_return ?? 0;
            $detailPenerimaan[$penerimaan->idpenerimaan][$key]->sub_total_terima -= $totalReturn;
        }
    }

    return view('penerimaan.list', [
        'penerimaanList' => $penerimaanList,
        'detailPenerimaan' => $detailPenerimaan,
        'idPengadaan' => $idPengadaan,
    ]);
}

 
}
