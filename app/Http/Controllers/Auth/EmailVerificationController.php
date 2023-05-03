<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;

class EmailVerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify-email');
    }

    public function request()
    {
        auth()->user()->sendEmailVerificationNotification();

        return back()
            ->with('success', 'Link verifikasi berhasil dikirim, silakan periksa email!');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('home')
            ->with('success','Email berhasil diverifikasi!');
        // return redirect()->to('/home'); // <-- change this to whatever you want
    }
}