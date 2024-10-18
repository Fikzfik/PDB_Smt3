<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VendorController extends Controller
{
    public function create(Request $request)
    {

        // Insert data menggunakan raw SQL
        $result = DB::insert('INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, ?)', [
            $request->input('nama_vendor'),
            $request->input('badan_hukum'),
            $request->input('status', 1)
        ]);

        if ($result) {
            $id = DB::getPdo()->lastInsertId();
            return response()->json(['idvendor' => $id, 'nama_vendor' => $request->input('nama_vendor')], 201);
        }

        return response()->json(['error' => 'Failed to add vendor'], 500);
    }

    public function delete($id)
    {
        $result = DB::delete('DELETE FROM vendor WHERE idvendor = ?', [$id]);

        if ($result) {
            return response()->json(['message' => 'Vendor deleted successfully'], 200);
        }

        return response()->json(['error' => 'Failed to delete vendor'], 500);
    }
}
