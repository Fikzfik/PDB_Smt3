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
}
