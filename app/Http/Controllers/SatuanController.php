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
    
        DB::insert('INSERT INTO satuan (nama_satuan, status) VALUES (?, ?)', [
            $request->input('nama_satuan'),
            $request->input('status', 1),
        ]);
        return redirect()->back();
    }

}
