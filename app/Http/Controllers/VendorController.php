<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VendorController extends Controller
{
    // VendorController.php
    public function update(Request $request, $id)
    {
        $result = DB::statement('CALL sp_update_vendor(?, ?, ?)', [$id, $request->input('nama_vendor'), $request->input('badan_hukum')]);

        if ($result) {
            return response()->json(['idvendor' => $id, 'nama_vendor' => $request->input('nama_vendor'), 'badan_hukum' => $request->input('badan_hukum'), 'status' => 1], 200);
        }

        return response()->json(['error' => 'Failed to update vendor'], 500);
    }

    public function create(Request $request)
    {
        $result = DB::select('CALL sp_create_vendor(?, ?, ?)', [$request->input('nama_vendor'), $request->input('badan_hukum'), $request->input('status', 1)]);

        if (!empty($result)) {
            return response()->json(['idvendor' => $result[0]->idvendor, 'nama_vendor' => $request->input('nama_vendor')], 201);
        }

        return response()->json(['error' => 'Failed to add vendor'], 500);
    }

    public function delete($id)
    {
        $result = DB::statement('CALL sp_delete_vendor(?)', [$id]);

        if ($result) {
            return response()->json(['message' => 'Vendor deleted successfully'], 200);
        }

        return response()->json(['error' => 'Failed to delete vendor'], 500);
    }
}
