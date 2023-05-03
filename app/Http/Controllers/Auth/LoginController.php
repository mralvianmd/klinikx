<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hakakses;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use DB;

class LoginController extends Controller
{
    

    public function show(Request $request)
    {
        if(session('login_user')){     
            $page_title = 'Dashboard';
            $page_description = 'Halaman dashboard';   
            if(session('akses_id') == 'ADM'){
                return redirect()->route('klinik');
            }else{
                return redirect()->route('klinik');
            }
        }else{
            $page_title = 'Signin';
            $page_description = 'Signin USER';
            $link = '';
            return view(('auth.signin'),compact('page_title', 'page_description','link'));
        }
    }

    public function handle(Request $request)
    {

        $page_title = 'Dashboard';
        $page_description = 'Halaman dashboard';   
    
        if(isset($request->email)){
            $email = $request->email;
        }else{
            $email = '';
        }

        if(isset($request->password)){
            $password = $request->password;
        }else{
            $password = '';
        }

        $success = auth()->attempt([
            'email' => request('email'),
            'password' => request('password')
        ], request()->has('remember'));

        if($success) {
            $get_hakakses = Hakakses::where('akses_id',Auth::user()->akses_id)->first();
            $get_menu = Menu::get();
            if(isset($get_hakakses->akses)){
                $request->session()->put([ 'login_user' => Auth::user()->name,
                    'akses_id' => Auth::user()->akses_id,
                    'email' => Auth::user()->email,
                    'authid' => Auth::user()->id,
                    'nama_akses' => $get_hakakses->akses,
                    'menu_id' => json_decode($get_hakakses->menu_id),
                    'all_menu' => $get_menu
                ]);
            }else{
                auth()->logout();
                session::flush();
                return redirect()->route('signin')->with('danger','Maaf, hak akses anda tidak ditemukan. Silakan hubungi admin, '.Auth::user()->name);
            }
            
            return redirect()->route('klinik')->with('success','Selamat datang kembali, '.Auth::user()->name);

                // return json_encode(['status' => 1,
                // 'akses_id' => Auth::user()->akses_id,
                // 'ket' => 'Berhasil login!',
                // 'link' => ''


        }

        return redirect()->back()->with('danger','Terdapat kesalahan email dan password, silakan ulangi');
        // return json_encode(['status' => 0,
        //                     'ket' => 'Terdapat kesalahan email dan password, silakan ulangi'
        //                     ]);

    }
}