<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Menggunakan DB facade
use Illuminate\Support\Facades\Log; // Menggunakan DB facade

class BarangController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenis' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'idsatuan' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        // Panggil stored procedure untuk insert
        DB::statement('CALL InsertBarang(?, ?, ?, ?)', [$request->input('jenis'), $request->input('nama'), $request->input('idsatuan'), $request->input('harga')]);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan.',
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        \Log::info($request->all());

        // Panggil stored procedure untuk update
        $updated = DB::statement('CALL UpdateBarang(?, ?, ?, ?, ?)', [$id, $request->input('jenis'), $request->input('nama'), $request->input('idsatuan'), $request->input('harga')]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil diperbarui.',
            ]);
        }

        return response()->json(['error' => 'Gagal memperbarui barang.'], 500);
    }

    public function delete($id)
    {
        // Panggil stored procedure untuk delete
        $deleted = DB::statement('CALL DeleteBarang(?)', [$id]);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus.',
            ]);
        }

        return response()->json(['error' => 'Gagal menghapus barang.'], 500);
    }
}