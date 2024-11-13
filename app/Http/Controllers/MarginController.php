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
    // Store Margin Penjualan
    public function store(Request $request)
    {
        $request->validate([
            'persen' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        // Get the current user ID (you may need to adjust this based on your auth system)
        $iduser = auth()->id();

        // Call the stored procedure for insert
        DB::statement('CALL InsertMarginPenjualan(?, ?, ?)', [
            $request->input('persen'),
            $request->input('status') === 'active' ? 1 : 0, // Convert 'active' to 1, 'inactive' to 0
            $iduser,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Margin Penjualan berhasil ditambahkan.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'persen' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        // Get the current user ID (you may need to adjust this based on your auth system)
        $iduser = auth()->id();

        // Call the stored procedure for update
        $updated = DB::statement('CALL UpdateMarginPenjualan(?, ?, ?, ?)', [
            $id,
            $request->input('persen'),
            $request->input('status') === 'active' ? 1 : 0, // Convert 'active' to 1, 'inactive' to 0
            $iduser,
        ]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Margin Penjualan berhasil diperbarui.',
            ]);
        }

        return response()->json(['error' => 'Gagal memperbarui margin penjualan.'], 500);
    }

    public function delete($id)
    {
        // Call the stored procedure for delete
        $deleted = DB::statement('CALL DeleteMarginPenjualan(?)', [$id]);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Margin Penjualan berhasil dihapus.',
            ]);
        }

        return response()->json(['error' => 'Gagal menghapus margin penjualan.'], 500);
    }
}
