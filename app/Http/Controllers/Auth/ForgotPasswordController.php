<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Illuminate\Http\Response; 
use DB; 
use Carbon\Carbon; 
use App\Models\User; 
use Mail;
use Hash;
use Illuminate\Support\Str;
use Validator;

class ForgotPasswordController extends Controller
{
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function showForgetPasswordForm()
      {
        $page_title = 'Reset Password';
        $page_description = 'Halaman reset password';   

         return view(('auth.passwords.forgetPassword'),compact('page_title', 'page_description'));
      }
  

      public function submitForgetPasswordForm(Request $request)
      {
          $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users'
          ]);
          
          if ($validator->fails()) {
            return json_encode(['status' => 0,
                            'ket' => 'Masukan alamat email yang sudah terdaftar!'
                            ]);
          } else {
            $token = Str::random(64);
            DB::table('password_resets')->insert([
                'email' => $request->email, 
                'token' => $token, 
                'created_at' => Carbon::now()
              ]);
    
            Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Reset Password Agenpos');
            });
            return json_encode(['status' => 1,
              'ket' => 'Berhasil mengirimkan email reset password<br>Silakan cek email kamu!'
            ]);
          }
      }
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function showResetPasswordForm($token) { 
        //  return view('auth.passwords.forgetPasswordLink', ['token' => $token]);
        $modal = '#ModalResetPasswordUser';
        return view('auth.login', ['modal' => $modal,'token' => $token,'link'=>'']);
      }
  
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitResetPasswordForm(Request $request)
      {
          $this->validate($request, [
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:6|confirmed',
              'password_confirmation' => 'required'
          ],
          [
            'email.required' => 'Masukan alamat email valid yang sudah terdaftar',
            'password.required' => 'Minimal password 6 karakter',
            'password_confirmation.required' => 'Password tidak sesuai'
          ]);
  
          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with('warning', 'Invalid token! Tidak bisa reset selain email anda');
          }
  
          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
 
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
  
          return redirect()->route('login')->with('success', 'Password berhasil diubah!');
      }
}