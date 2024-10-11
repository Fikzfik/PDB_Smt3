<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Menggunakan DB facade

class BarangController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'idsatuan' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $id = DB::table('barang')->insertGetId([
            'jenis' => $request->input('jenis'),
            'nama' => $request->input('nama'),
            'idsatuan' => $request->input('idsatuan'),
            'harga' => $request->input('harga'),
            'status' => $request->input('status', 1),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan.',
            'data' => [
                'idbarang' => $id,
                'jenis' => $request->input('jenis'),
                'nama' => $request->input('nama'),
                'idsatuan' => $request->input('idsatuan'),
                'harga' => $request->input('harga'),
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'idsatuan' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $updated = DB::update('UPDATE barang SET jenis = ?, nama = ?, idsatuan = ?, harga = ? WHERE idbarang = ?', [$request->input('jenis'), $request->input('nama'), $request->input('idsatuan'), $request->input('harga'), $id]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil diperbarui.',
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Barang gagal diperbarui.',
                ],
                500,
            );
        }
    }

    public function delete($id)
    {
        $deleted = DB::delete('DELETE FROM barang WHERE idbarang = ?', [$id]);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus.',
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Barang gagal dihapus.',
                ],
                500,
            );
        }
    }

    public function edit($id)
    {
        $barang = DB::select('SELECT * FROM barang WHERE idbarang = ?', [$id]);

        if (empty($barang)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Barang tidak ditemukan.',
                ],
                404,
            );
        }

        return response()->json([
            'success' => true,
            'data' => $barang[0],
        ]);
    }
    public function history($id)
    {
        $history = DB::select('SELECT * FROM kartu_stok WHERE idbarang = ? ORDER BY created_at DESC', [$id]);
        @dd($history);
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
