<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));
        $idrole = $request->input('idrole');
        $status = $request->input('status', 1);

        // Menggunakan stored procedure untuk insert data
        $result = DB::statement('CALL sp_create_user(?, ?, ?, ?, ?, @id)', [$username, $email, $password, $idrole, $status]);

        // Mengambil nilai ID yang dihasilkan oleh prosedur
        $id = DB::select('SELECT @id AS id')[0]->id;

        if ($result) {
            return response()->json(
                [
                    'id' => $id,
                    'username' => $username,
                    'email' => $email,
                    'idrole' => $idrole,
                    'status' => $status,
                ],
                201,
            );
        }

        return response()->json(['error' => 'Failed to add user'], 500);
    }

    public function update(Request $request, $iduser)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));
        $idrole = $request->input('idrole');

        // Menggunakan stored procedure untuk update data
        $result = DB::statement('CALL sp_update_user(?, ?, ?, ?, ?)', [$iduser, $username, $email, $password, $idrole]);

        if ($result) {
            return response()->json(['success' => true, 'message' => 'User updated successfully!']);
        }

        return response()->json(['error' => 'Failed to update user'], 500);
    }

    public function delete($iduser)
    {
        // Menggunakan stored procedure untuk delete data
        $result = DB::statement('CALL sp_delete_user(?)', [$iduser]);

        if ($result) {
            return response()->json(['success' => true, 'message' => 'User deleted successfully!']);
        }

        return response()->json(['error' => 'Failed to delete user'], 500);
    }
}
