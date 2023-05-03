<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hakakses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(session('login_user')){
            $page_title = 'User';
            $page_description = 'Halaman kelola user';
            $get_hakakses = Hakakses::get();

            return view('kelola.user',compact('page_title',
            'page_description',
            'get_hakakses'
            ));
        }else{
            return redirect('signin')
            ->with('warning','Maaf, anda diharuskan login!');
        }
    }

    public function get_user(Request $request)
    {
        if(session('login_user')){      
            $get_user = User::get();
            $get_hakakses = Hakakses::get();

            if(count($get_user) != 0){
                $no = 1;
                $html='';
                $html.='
                    <table id="tlist_user" class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Akses ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                            <th>Tanggal Pembuatan</th>
                            <th>Tanggal Update</th>
                        </tr>
                    </thead>
                        <tbody>
                    ';
                foreach($get_user as $user){
                    $id=$user->id;
                    $akses_id=$user->akses_id;
                    foreach($get_hakakses as $hakakses){
                        if($hakakses->akses_id == $akses_id){
                            $nama_akses = $hakakses->akses;
                        }
                    }
                    $nama=$user->name;
                    $email=$user->email;
                    $created_at=$user->created_at;
                    if($user->updated_at == '' || $user->updated_at == null){
                        $updated_at='';
                    }else{
                        $updated_at=date('Y-m-d H:i:s',strtotime($user->updated_at));
                    }

                    $html.='
                    <tr>
                        <td>'.$no.'</td>
                        <td>'.strtoupper($akses_id).' / '.ucwords($nama_akses).'</td>
                        <td>'.ucwords($nama).'</td>
                        <td>'.($email).'</td>
                        <td>';
                            $html.='                                
                            <button data-toggle="modal" data-target="#ModalEdit" onclick=edit('."'".Crypt::encryptString($id)."'".') class="btn btn-primary btn-sm px-2 py-1">
                            Edit
                            </button>
                            ';
                            $html.='                                
                            <button onclick=confirm_delete('."'".Crypt::encryptString($id)."'".') class="btn btn-danger btn-sm px-2 py-1">
                            Hapus
                            </button>
                            ';
                        $html.='
                        </td>
                        <td>'.date('Y-m-d H:i:s',strtotime($created_at)).'</td>
                        <td>'.$updated_at.'</td>
                    </tr>         
                    
                ';

                $no++;
                }
                
                $html.='</tbody>
                </table>';

                return json_encode([
                            'status' => 1,
                            'ket' => 'Berhasil mendapatkan data',
                            't_user' => $html
                        ]);
            }else{
                return json_encode([
                            'status' => 0,
                            'ket' => 'Data kosong',
                            't_user' => ''
                        ]);
            }
            // -------------------- End Statistik
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function fill_user(Request $request)
    {
        if(session('login_user')){
            $get_hakakses = Hakakses::get();
            $id = Crypt::decryptString($request->id);
            $fill_user = User::where('id',intval($id))->first();
            if(isset($fill_user->name)){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Data ditemukan',
                    'isi' => $fill_user,
                    'id' => $request->id,
                    'get_hakakses' => $get_hakakses
                ]);
            }else{
                return json_encode([
                    'status' => 0,
                    'ket' => 'Data kosong',
                    'isi' => ''
                ]);
            }
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function edit_user(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->user_id);
            $nama=ucwords($request->nama);
            $email=$request->email;
            $akses_id=$request->akses_id;

            if(!empty($request->password)){
                $edit_user = User::where('id',$id)
                ->update([
                    'name' => $nama,
                    'email' => $email,
                    'akses_id' => $akses_id,
                    'password' => Hash::make($request->password)
                ]);
            }else{
                $edit_user = User::where('id',$id)
                ->update([
                    'name' => $nama,
                    'email' => $email,
                    'akses_id' => $akses_id
                ]);
            }

            if($edit_user){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Berhasil update user '.$nama
                ]);
            }else{
                return json_encode([
                    'status' => 0,
                    'ket' => 'Gagal update, silakan coba kembali'
                ]);
            }
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function add_user(Request $request)
    {
        if(session('login_user')){
            $nik=$request->nik;
            $nama=ucwords($request->nama);
            $email=$request->email;
            $akses_id=$request->akses_id;
            $password=$request->password;

            $find_user = User::where('email',$email)->first();

            if(isset($find_user->email)){
                return json_encode([
                    'status' => 0,
                    'ket' => 'Gagal, email sudah terdaftar'
                ]);
            }else{
                $add_user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'akses_id' => $akses_id,
                    'password' => Hash::make($password)
                ]);
    
                if($add_user){
                    return json_encode([
                        'status' => 1,
                        'ket' => 'Berhasil tambah user '.$nama
                    ]);
                }else{
                    return json_encode([
                        'status' => 0,
                        'ket' => 'Gagal tambah, silakan coba kembali'
                    ]);
                }
            }
            
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }
    
    public function destroy(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->id);
            $delete_user = User::where('id',$id)->delete();
            if($delete_user){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Berhasil hapus data',
                ]);
            }else{
                return json_encode([
                    'status' => 0,
                    'ket' => 'Gagal hapus, silakan coba kembali',
                ]);
            }
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }
}
