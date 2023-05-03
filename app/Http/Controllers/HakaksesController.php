<?php

namespace App\Http\Controllers;

use App\Models\Hakakses;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class HakaksesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request)
    {
        if(session('login_user')){
            $page_title = 'Hak Akses';
            $page_description = 'Halaman kelola hak akses';

            $get_menu = Menu::get();
            return view('kelola.hakakses',compact('page_title',
            'page_description',
            'get_menu'
            ));
        }else{
            return redirect('signin')
            ->with('warning','Maaf, anda diharuskan login!');
        }
    }

    public function get_hakakses(Request $request)
    {
        if(session('login_user')){      
            $get_hakakses = Hakakses::get();
            $get_menu = Menu::get();

            if(count($get_hakakses) != 0){
                $no = 1;
                $html='';
                $html.='
                    <table id="tlist_hakakses" class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Akses ID</th>
                            <th>Akses</th>
                            <th>Deskripsi</th>
                            <th>Menu</th>
                            <th>Aksi</th>
                            <th>Tanggal Pembuatan</th>
                            <th>Tanggal Update</th>
                        </tr>
                    </thead>
                        <tbody>
                    ';
                foreach($get_hakakses as $hakakses){
                    $all_list_akses = '';
                    $akses_id=$hakakses->akses_id;
                    $akses=$hakakses->akses;
                    $deskripsi=$hakakses->deskripsi;
                    if(($hakakses->menu_id == '') || ($hakakses->menu_id == null)){
                        $list_akses = '-';
                    }else{
                        $menu_id=json_decode($hakakses->menu_id);
                        foreach($get_menu as $all_menu){
                            for($i=0;$i<count($menu_id);$i++){
                                if($all_menu->menu_id == $menu_id[$i]){
                                    $all_list_akses = $all_menu->deskripsi.', '.$all_list_akses;
                                }
                            }
                        }
                        $list_akses = substr($all_list_akses,0,-2);
                    }
                    
                    $created_at=$hakakses->created_at;
                    if($hakakses->updated_at == '' || $hakakses->updated_at == null){
                        $updated_at='';
                    }else{
                        $updated_at=date('Y-m-d H:i:s',strtotime($hakakses->updated_at));
                    }

                    $html.='
                    <tr>
                        <td>'.$no.'</td>
                        <td><span class="label label-inline">'.$akses_id.'</span></td>
                        <td>'.ucwords($akses).'</td>
                        <td>'.ucwords($deskripsi).'</td>
                        <td>'.$list_akses.'</td>
                        <td>';
                            $html.='                                
                            <button data-toggle="modal" data-target="#ModalEdit" onclick=edit('."'".Crypt::encryptString($akses_id)."'".') class="btn btn-primary btn-sm px-2 py-1">
                            Edit
                            </button>
                            ';
                            $html.='                                
                            <button onclick=confirm_delete('."'".Crypt::encryptString($akses_id)."'".') class="btn btn-danger btn-sm px-2 py-1">
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
                            't_hakakses' => $html
                        ]);
            }else{
                return json_encode([
                            'status' => 0,
                            'ket' => 'Data kosong',
                            't_hakakses' => ''
                        ]);
            }
            // -------------------- End Statistik
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function fill_hakakses(Request $request)
    {
        if(session('login_user')){
            $get_menu = Menu::get();
            $id = Crypt::decryptString($request->id);
            $fill_hakakses = Hakakses::where('akses_id',$id)->first();
            if(isset($fill_hakakses->akses)){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Data ditemukan',
                    'isi' => $fill_hakakses,
                    'get_menu' => $get_menu
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

    public function edit_hakakses(Request $request)
    {
        if(session('login_user')){
            $akses_id = $request->akses_id;
            $akses = $request->akses;
            $deskripsi = $request->deskripsi;
            if(!empty($request->menu_id)){
                $menu_id = json_encode($request->menu_id);
            }else{
                $menu_id = null;
            }

            $edit_hakakses = Hakakses::where('akses_id',$akses_id)
            ->update([
                'akses' => $akses,
                'deskripsi' => $deskripsi,
                'menu_id' => $menu_id,
            ]);

            if($edit_hakakses){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Berhasil update akses '.$akses
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

    public function add_hakakses(Request $request)
    {
        if(session('login_user')){
            $akses_id = $request->akses_id;
            $akses = $request->akses;
            $deskripsi = $request->deskripsi;
            if(!empty($request->menu_id)){
                $menu_id = json_encode($request->menu_id);
            }else{
                $menu_id = null;
            }

            $find_hakakses = Hakakses::where('akses_id',$akses_id)->first();

            if(isset($find_hakakses->akses_id)){
                return json_encode([
                    'status' => 0,
                    'ket' => 'Gagal, akses ID sudah digunakan'
                ]);
            }else{
                $add_hakakses = Hakakses::create([
                    'akses_id' => $akses_id,
                    'akses' => $akses,
                    'deskripsi' => $deskripsi,
                    'menu_id' => $menu_id
                ]);
    
                if($add_hakakses){
                    return json_encode([
                        'status' => 1,
                        'ket' => 'Berhasil tambah akses '.$akses
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
            $delete_hakakses = Hakakses::where('akses_id',$id)->delete();
            if($delete_hakakses){
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
