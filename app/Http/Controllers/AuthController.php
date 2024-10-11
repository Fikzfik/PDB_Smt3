<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Insert (Create) User
    public function create(Request $request)
    {
        // @dd($request);
        DB::insert('INSERT INTO users (username, email, password, idrole) VALUES (?, ?, ?, ?)', [$request->input('username'), $request->input('email'), bcrypt($request->input('password')), $request->input('idrole')]);
        return redirect()->back()->with('success', 'User created successfully!');
    }
    public function update(Request $request, $iduser)
    {
        DB::update('UPDATE users SET username = ?, email = ?, password = ?, idrole = ? WHERE iduser = ?', [$request->input('username'), $request->input('email'), bcrypt($request->input('password')), $request->input('idrole'), $iduser]);

        return redirect()->back()->with('success', 'User updated successfully!');
    }
    public function delete($iduser)
    {
        DB::delete('DELETE FROM users WHERE iduser = ?', [$iduser]);
        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    public function loginpost(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $query = 'SELECT * FROM users JOIN role ON role.idrole = users.idrole WHERE users.email = ?';
        $validUser = DB::select($query, [$email]);

        if (!empty($validUser)) {
            $user = $validUser[0];

            if (Hash::check($password, $user->password)) {
                if ($user->status === null || $user->status == 0) {
                    DB::update('UPDATE users SET status = 1 WHERE iduser = ?', [$user->iduser]);
                }
                Auth::loginUsingId($user->iduser);
                // @dd(Auth::user()->status);
                return redirect()->route('index');
            } else {
                return redirect()->back()->with('error', 'Password salah.');
            }
        } else {
            return redirect()->back()->with('error', 'Email tidak ditemukan.');
        }
    }
    public function logout($id)
    {
        $user = DB::select('SELECT * FROM users WHERE status = 1');
        if ($user) {
            Auth::logout();
            $user = $user[0];  // Mengakses elemen pertama dari array yang merupakan objek
            DB::update('UPDATE users SET status = 0 WHERE iduser = ?', [$user->iduser]);
            return redirect('/login')->with('success', 'Anda telah berhasil logout.');
        }

        return redirect()->back()->with('error', 'Logout gagal, pengguna tidak ditemukan.');
    }
}
