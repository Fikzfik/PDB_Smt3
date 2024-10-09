<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Menggunakan DB facade

class BarangController extends Controller
{
    // Menampilkan form untuk menambahkan barang
    public function create()
    {
        // Ambil data satuan untuk ditampilkan di select dropdown
        $satuan = DB::table('satuan')->get();
        return view('barang.create', compact('satuan'));
    }

    // Menyimpan data barang
    public function store(Request $request)
    {
        DB::insert('INSERT INTO barang (jenis, nama, idsatuan, harga, status) VALUES (?, ?, ?, ?, ?)', 
        [
            $request->input('jenis'), 
            $request->input('nama'),
            $request->input('idsatuan'),
            $request->input('harga'),
            $request->input('status', 1)
        ]);

        return redirect()->route('barang')->with('success', 'Barang berhasil ditambahkan.');
    }
    public function update(Request $request, $id)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'jenis' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'idsatuan' => 'required|integer',
            'harga' => 'required|numeric',
        ]);
        // Update data barang
        
        $updated = DB::update('UPDATE barang SET jenis = ?, nama = ?, idsatuan = ?, harga = ? WHERE idbarang = ?', [
            $request->input('jenis'),
            $request->input('nama'), 
            $request->input('idsatuan'),
            $request->input('harga'),
            $id
        ]);
        
        // Mengecek apakah update berhasil
        if ($updated) {
            return redirect()->route('barang')->with('success', 'Barang berhasil diperbarui.');
        } else {
            return redirect()->route('barang')->with('error', 'Barang gagal diperbarui.');
        }
    }
    public function delete($id)
    {
        // Menghapus data barang berdasarkan id
        $deleted = DB::delete('DELETE FROM barang WHERE idbarang = ?', [$id]);
        
        // Mengecek apakah delete berhasil
        if ($deleted) {
            return redirect()->route('barang')->with('success', 'Barang berhasil dihapus.');
        } else {
            return redirect()->route('barang')->with('error', 'Barang gagal dihapus.');
        }
    }
    public function edit($id)
    {
        // Mengambil data barang berdasarkan id
        $barang = DB::select('SELECT * FROM barang WHERE idbarang = ?', [$id]);
    
        // Mengecek jika barang ditemukan
        if (empty($barang)) {
            return redirect()->route('barang')->with('error', 'Barang tidak ditemukan.');
        }
    
        // Mengirim data barang ke view
        return view('editbarang', ['barang' => $barang[0]]);
    }
}
