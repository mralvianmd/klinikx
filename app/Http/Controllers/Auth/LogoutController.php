<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function handle()
    {
        $page_title = 'Signin';
        $page_description = 'Signin USER'; 
        auth()->logout();
        session::flush();
        return redirect()->route('signin');
    }
}