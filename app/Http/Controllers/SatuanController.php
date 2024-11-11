<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SatuanController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255',
        ]);

        // Memanggil stored procedure InsertSatuan
        $result = DB::select('CALL InsertSatuan(?, ?)', [$request->input('nama_satuan'), $request->input('status', 1)]);

        if ($result) {
            // Mendapatkan ID yang baru saja dimasukkan
            $id = $result[0]->idsatuan;
            return response()->json(['idsatuan' => $id, 'nama_satuan' => $request->nama_satuan], 201);
        }

        return response()->json(['error' => 'Failed to add stock unit'], 500);
    }
    public function delete($id)
    {
        // Memanggil stored procedure DeleteSatuan
        $result = DB::select('CALL DeleteSatuan(?)', [$id]);

        if ($result) {
            return response()->json(['success' => 'Stock Unit deleted successfully'], 200);
        }

        return response()->json(['error' => 'Failed to delete stock unit'], 500);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255',
        ]);

        // Memanggil stored procedure UpdateSatuan
        $result = DB::select('CALL UpdateSatuan(?, ?)', [$id, $request->input('nama_satuan')]);

        if ($result) {
            return response()->json(['idsatuan' => $id, 'nama_satuan' => $request->input('nama_satuan')], 200);
        }

        return response()->json(['error' => 'Failed to update stock unit'], 500);
    }
}
