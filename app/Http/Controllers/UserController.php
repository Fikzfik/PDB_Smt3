<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function create(Request $request)
    {
        // Insert data menggunakan raw SQL
        $result = DB::insert('INSERT INTO users (username, email, password, idrole, status) VALUES (?, ?, ?, ?, ?)', [
            $request->input('username'),
            $request->input('email'),
            bcrypt($request->input('password')),
            $request->input('idrole'),
            $request->input('status', 1)
        ]);

        if ($result) {
            $id = DB::getPdo()->lastInsertId();
            return response()->json([
                'id' => $id,
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'idrole' => $request->input('idrole'),
                'status' => $request->input('status', 1)
            ], 201);
        }

        return response()->json(['error' => 'Failed to add user'], 500);
    }

    public function delete($id)
    {
        $result = DB::delete('DELETE FROM users WHERE id = ?', [$id]);

        if ($result) {
            return response()->json(['message' => 'User deleted successfully'], 200);
        }

        return response()->json(['error' => 'Failed to delete user'], 500);
    }
}
