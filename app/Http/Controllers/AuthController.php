<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // VALIDASI
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [

            // NAMA
            'name.required' => 'Nama tenant wajib diisi.',
            'name.max' => 'Nama tenant terlalu panjang.',

            // EMAIL
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            // PASSWORD
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',

        ]);

        // INSERT USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'tenant',
        ]);

        Tenant::create([
        'user_id' => $user->id,
        'nama_tenant' => $request->name,
        ]);

        // AUTO LOGIN
        Auth::login($user);

        // REDIRECT
        return redirect()->route('tenant.dashboard');
    }

    public function showLogin()
{
    return view('auth.login');
}

    /*
    |--------------------------------------------------------------------------
    | LOGIN PAGE
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN PROCESS
    |--------------------------------------------------------------------------
    */

    public function authenticate(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK LOGIN
        |--------------------------------------------------------------------------
        */

        if (Auth::attempt($credentials)) {

            /*
            |--------------------------------------------------------------------------
            | REGENERATE SESSION
            |--------------------------------------------------------------------------
            */

            $request->session()->regenerate();

            /*
            |--------------------------------------------------------------------------
            | REDIRECT BERDASARKAN ROLE
            |--------------------------------------------------------------------------
            */

            if (Auth::user()->role === 'admin') {
                return redirect('/admin');
            }

            if (Auth::user()->role === 'tenant') {
                return redirect('/tenant');
            }

            return redirect('/');
        }

        /*
        |--------------------------------------------------------------------------
        | JIKA GAGAL LOGIN
        |--------------------------------------------------------------------------
        */

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->onlyInput('email');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
