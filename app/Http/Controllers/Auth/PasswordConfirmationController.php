<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordConfirmationController extends Controller
{
    public function show()
    {
        return view('auth.passwords.confirm');
    }

    public function handle()
    {
        if (!Hash::check(request()->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Password yang dimasukkan tidak sesuai']);
        }

        session()->passwordConfirmed();

        return redirect()->intended();
    }
}