<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function create(Request $request)
    {
        // Tambahkan dd() untuk debugging
        dd('Masuk ke controller create role', $request->all());

        // Proses selanjutnya tidak akan dijalankan karena dd akan menghentikan eksekusi
    }

    public function delete($id)
    {
        // Hapus role berdasarkan ID menggunakan raw SQL
        $result = DB::delete('DELETE FROM role WHERE idrole = ?', [$id]);

        if ($result) {
            return response()->json(['success' => 'Role deleted successfully'], 200);
        }

        return response()->json(['error' => 'Failed to delete role'], 500);
    }
}
