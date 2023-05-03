<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(session('login_user')){
            $page_title = 'Obat';
            $page_description = 'Halaman kelola obat';

            return view('kelola.obat',compact('page_title',
            'page_description'
            ));
        }else{
            return redirect('signin')
            ->with('warning','Maaf, anda diharuskan login!');
        }
    }

    public function get_obat(Request $request)
    {
        if(session('login_user')){      
            $get_obat = Obat::get();

            if(count($get_obat) != 0){
                $no = 1;
                $html='';
                $html.='
                    <table id="tlist_obat" class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Obat</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                            <th>Tanggal Pembuatan</th>
                            <th>Tanggal Update</th>
                        </tr>
                    </thead>
                        <tbody>
                    ';
                foreach($get_obat as $obat){
                    $id=$obat->id;
                    $nama_obat=$obat->obat;
                    $jumlah=$obat->qty;
                    $harga=number_format($obat->harga,0,'.',',');
                    $created_at=$obat->created_at;
                    if($obat->updated_at == '' || $obat->updated_at == null){
                        $updated_at='';
                    }else{
                        $updated_at=date('Y-m-d H:i:s',strtotime($obat->updated_at));
                    }

                    $html.='
                    <tr>
                        <td>'.$no.'</td>
                        <td>'.ucwords($nama_obat).'</td>
                        <td>'.ucwords($jumlah).'</td>
                        <td>'.$harga.'</td>
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
                            't_obat' => $html
                        ]);
            }else{
                return json_encode([
                            'status' => 0,
                            'ket' => 'Data kosong',
                            't_obat' => ''
                        ]);
            }
            // -------------------- End Statistik
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function fill_obat(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->id);
            $fill_obat = Obat::where('id',$id)->first();
            if(isset($fill_obat->obat)){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Data ditemukan',
                    'isi' => $fill_obat,
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

    public function edit_obat(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->obat_id);
            $obat = $request->obat;
            $jumlah = intval(preg_replace('/\D/','',$request->jumlah));
            $harga = intval(preg_replace('/\D/','',$request->harga));
            $get_obat = Obat::where('id',$id)->first();

            $edit_obat = Obat::where('id',$id)
            ->update([
                'obat' => $obat,
                'qty' => $jumlah,
                'harga' => $harga,
                'created_by' => session('authid')
            ]);
            if($get_obat->qty < $jumlah){
                $insert_riwayat_obat = DB::table('riwayat_obats')->insert([
                    'obat_id' => $id,
                    'qty_in' => intval($jumlah-$get_obat->qty),
                    'user_id' => intval(session('authid'))
                ]);
            }elseif($get_obat->qty > $jumlah){
                $insert_riwayat_obat = DB::table('riwayat_obats')->insert([
                    'obat_id' => $id,
                    'qty_out' => intval($get_obat->qty-$jumlah),
                    'user_id' => intval(session('authid'))
                ]);
            }

            if($edit_obat){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Berhasil update obat '.$obat
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

    public function add_obat(Request $request)
    {
        if(session('login_user')){
            $obat = $request->obat;
            $jumlah = intval(preg_replace('/\D/','',$request->jumlah));
            $harga = intval(preg_replace('/\D/','',$request->harga));

            $find_obat = Obat::where('obat',strtolower($obat))->first();

            if(isset($find_obat->obat)){
                return json_encode([
                    'status' => 0,
                    'ket' => 'Gagal, Obat sudah ada'
                ]);
            }else{
                $add_obat = Obat::create([
                    'obat' => $obat,
                    'qty' => $jumlah,
                    'harga' => $harga,
                    'created_by' => session('authid')
                ]);
    
                if($add_obat){
                    return json_encode([
                        'status' => 1,
                        'ket' => 'Berhasil tambah obat '.$obat
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
            $delete_obat = Obat::where('id',$id)->delete();
            if($delete_obat){
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
