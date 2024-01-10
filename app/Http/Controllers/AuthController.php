<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login', [
            'title' => 'Login',
            'active' => 'login'
        ]);
    }

    public function register()
    {
        return view('auth.register', [
            'title' => 'Register',
            'active' => 'register'
        ]);
    }

    public function storeRegister(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255', // pipe format
            'username' => ['required', 'min:3', 'max:255', 'unique:users'], // array format
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:5|max:255'
        ]);

        // Menggunakan bcypt
        //$validatedData['password'] = bcrypt($validatedData['password']);

        // Menggunakan Hash
        // $validatedData['password'] = Hash::make($validatedData['password']);

        // laravel-10 diperkenalkan casting hashed
        // no-code

        User::create($validatedData);

        // Versi Session Flash
        // $request->session()->flash('success', 'Registration successfull! Please login');

        return redirect('/login')->with('success', 'Registration successfull! Please login'); // Versi shorthand, redirect sekaligus membawa flash message
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email:dns'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        // Error disini akan masuk kedalam variabel @error
        // return back()->withErrors('');

        // Menggunakan flash message sebagai informasi error
        return back()->with('loginError', 'Login failed!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session agar tidak bisa digunakan
        $request->session()->invalidate();

        // Regenerate Token atau bikin baru agar tidak dibajak
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
