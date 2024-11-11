<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function create(Request $request)
    {
        $nama_role = $request->input('nama_role');
        $result = DB::statement('CALL addRole(?)', [$nama_role]);

        if ($result) {
            $idrole = DB::getPdo()->lastInsertId();
            return response()->json(['idrole' => $idrole, 'nama_role' => $nama_role], 201);
        }

        return response()->json(['error' => 'Failed to add role'], 500);
    }

    public function update(Request $request, $id)
    {
        $nama_role = $request->input('nama_role');
        $result = DB::statement('CALL updateRole(?, ?)', [$id, $nama_role]);

        if ($result) {
            return response()->json(['idrole' => $id, 'nama_role' => $nama_role, 'message' => 'Role updated successfully'], 200);
        }

        return response()->json(['error' => 'Failed to update role'], 500);
    }

    public function delete($id)
    {
        $result = DB::statement('CALL deleteRole(?)', [$id]);

        if ($result) {
            return response()->json(['success' => 'Role deleted successfully'], 200);
        }

        return response()->json(['error' => 'Failed to delete role'], 500);
    }
}
