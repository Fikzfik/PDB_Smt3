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

        // Insert new satuan into the database using raw SQL
        $result =   DB::insert('INSERT INTO satuan (nama_satuan, status) VALUES (?, ?)', [$request->input('nama_satuan'), $request->input('status', 1)]);

        if ($result) {
            // Get the newly inserted ID and name
            $id = DB::getPdo()->lastInsertId();
            return response()->json(['idsatuan' => $id, 'nama_satuan' => $request->nama_satuan], 201);
        }

        return response()->json(['error' => 'Failed to add stock unit'], 500);
    }

    // Delete a Stock Unit
    public function delete($id)
    {
        // Delete the specified satuan using raw SQL
        $result = DB::delete('DELETE FROM satuan WHERE idsatuan = ?', [$id]);

        if ($result) {
            return response()->json(['success' => 'Stock Unit deleted successfully'], 200);
        }

        return response()->json(['error' => 'Failed to delete stock unit'], 500);
    }
}
