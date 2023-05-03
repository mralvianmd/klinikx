<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(session('login_user')){
            $page_title = 'Menu';
            $page_description = 'Halaman kelola menu';

            return view('kelola.menu',compact('page_title',
            'page_description'
            ));
        }else{
            return redirect('signin')
            ->with('warning','Maaf, anda diharuskan login!');
        }
    }

    public function get_menu(Request $request)
    {
        if(session('login_user')){      
            $get_menu = Menu::get();

            if(count($get_menu) != 0){
                $no = 1;
                $html='';
                $html.='
                    <table id="tlist_menu" class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Menu ID</th>
                            <th>Deskripsi</th>
                            <th>Route</th>
                            <th>Aksi</th>
                            <th>Tanggal Pembuatan</th>
                            <th>Tanggal Update</th>
                        </tr>
                    </thead>
                        <tbody>
                    ';
                foreach($get_menu as $menu){
                    $menu_id=$menu->menu_id;
                    $deskripsi=$menu->deskripsi;
                    $route=$menu->route;
                    $created_at=$menu->created_at;
                    if($menu->updated_at == '' || $menu->updated_at == null){
                        $updated_at='';
                    }else{
                        $updated_at=date('Y-m-d H:i:s',strtotime($menu->updated_at));
                    }

                    $html.='
                    <tr>
                        <td>'.$no.'</td>
                        <td>'.strtoupper($menu_id).'</td>
                        <td>'.ucwords($deskripsi).'</td>
                        <td>'.$route.'</td>
                        <td>';
                            $html.='                                
                            <button data-toggle="modal" data-target="#ModalEdit" onclick=edit('."'".Crypt::encryptString($menu_id)."'".') class="btn btn-primary btn-sm px-2 py-1">
                            Edit
                            </button>
                            ';
                            $html.='                                
                            <button onclick=confirm_delete('."'".Crypt::encryptString($menu_id)."'".') class="btn btn-danger btn-sm px-2 py-1">
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
                            't_menu' => $html
                        ]);
            }else{
                return json_encode([
                            'status' => 0,
                            'ket' => 'Data kosong',
                            't_menu' => ''
                        ]);
            }
            // -------------------- End Statistik
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function fill_menu(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->id);
            $fill_menu = Menu::where('menu_id',($id))->first();
            if(isset($fill_menu->menu_id)){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Data ditemukan',
                    'isi' => $fill_menu,
                    'id' => $request->id
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

    public function edit_menu(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->menu_id);
            $deskripsi=ucwords($request->deskripsi);
            $route=$request->route;
            $edit_menu = Menu::where('menu_id',$id)
            ->update([
                'deskripsi' => $deskripsi,
                'route' => $route
            ]);

            if($edit_menu){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Berhasil update menu '.$deskripsi
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

    public function add_menu(Request $request)
    {
        if(session('login_user')){
            $menu_id = $request->menu_id;
            $deskripsi=ucwords($request->deskripsi);
            $route=$request->route;

            $find_menu = Menu::where('menu_id',strtoupper($menu_id))->first();

            if(isset($find_menu->menu_id)){
                return json_encode([
                    'status' => 0,
                    'ket' => 'Gagal, Menu sudah terdaftar'
                ]);
            }else{
                $add_menu = Menu::create([
                    'menu_id' => $menu_id,
                    'deskripsi' => $deskripsi,
                    'route' => $route
                ]);
    
                if($add_menu){
                    return json_encode([
                        'status' => 1,
                        'ket' => 'Berhasil tambah menu '.$deskripsi
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
            $delete_menu = Menu::where('menu_id',$id)->delete();
            if($delete_menu){
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
