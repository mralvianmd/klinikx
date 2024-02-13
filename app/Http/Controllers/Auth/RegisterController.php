<?php

// app/Http/Controller/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Menu;
use App\Models\Hakakses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        $get_hakakses = Hakakses::get();
        return  view(('auth.signup'),compact('get_hakakses'));
    }

    public function handle(Request $request)
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required'],
            'akses_id' => ['required']
        ]);

        $available_user = User::where('email',request('email'))->first();
        if(isset($available_user->email)){
            return redirect()->back()->with('danger','Maaf, email '.request('email').' sudah terdaftar ');
        }else{
            $user = User::create([
                'name' => request('name'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
                'created_at' => date('Y-m-d H:i:s'),
                'akses_id' => request('akses_id'),
            ]);
    
            event(new Registered($user));
            Auth::login($user);
            $get_menu = Menu::get();
            $get_hakakses = Hakakses::where('akses_id',Auth::user()->akses_id)->first();
            $request->session()->put([  'login_user' => Auth::user()->name,
                    'akses_id' => Auth::user()->akses_id,
                    'email' => Auth::user()->email,
                    'authid' => Auth::user()->id,
                    'nama_akses' => $get_hakakses->akses,
                    'menu_id' => json_decode($get_hakakses->menu_id),
                    'all_menu' => $get_menu
            ]);
            return redirect()->route('klinik')->with('success','Selamat datang kembali, '.Auth::user()->name);
    
        }
        
        // return json_encode(['status' => 1,
        //                     'ket' => 'Berhasil daftar!<br>Otomatis melakukan login'
        //                     ]);
    }
}