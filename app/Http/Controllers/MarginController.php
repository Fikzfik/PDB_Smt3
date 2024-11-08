<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class MarginController extends Controller
{
    public function getMargins()
    {
        // Mengambil semua margin penjualan menggunakan raw SQL
        $margins = DB::select('SELECT * FROM margin_penjualan');

        // Mengirim data margin penjualan ke frontend
        return response()->json(['margins' => $margins]);
    }
    public function store(Request $request)
    {
        $status = $request->input('status') == 'active' ? 1 : 0; // Set status to 1 if 'active', otherwise 0

        $result = DB::insert(
            'INSERT INTO margin_penjualan (persen, status, iduser, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?)',
            [
                $request->input('persen'),
                $status, // Using the computed status
                Auth::id(), // Authenticated user's ID
                now(), // current timestamp for created_at
                now(), // current timestamp for updated_at
            ],
        );

        // Check if the insert was successful
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Margin Penjualan berhasil ditambahkan.',
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menambahkan Margin Penjualan.',
                ],
                500,
            );
        }
    }

    public function updateMarginPenjualan(Request $request, $id)
    {
        $request->validate([
            'persen' => 'required|numeric',
            'status' => 'required|boolean',
        ]);

        $updated = DB::update('UPDATE margin_penjualan SET persen = ?, status = ? WHERE idmargin_penjualan = ?', [$request->input('persen'), $request->input('status'), $id]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Margin Penjualan berhasil diperbarui.',
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Margin Penjualan gagal diperbarui.',
                ],
                500,
            );
        }
    }

    public function deleteMarginPenjualan($id)
    {
        $deleted = DB::delete('DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?', [$id]);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Margin Penjualan berhasil dihapus.',
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Margin Penjualan gagal dihapus.',
                ],
                500,
            );
        }
    }

    public function editMarginPenjualan($id)
    {
        $marginPenjualan = DB::select('SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?', [$id]);

        if (empty($marginPenjualan)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Margin Penjualan tidak ditemukan.',
                ],
                404,
            );
        }

        return response()->json([
            'success' => true,
            'data' => $marginPenjualan[0],
        ]);
    }
}
