<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(session('login_user')){
            $page_title = 'Pasien';
            $page_description = 'Halaman kelola pasien';

            return view('kelola.pasien',compact('page_title',
            'page_description'
            ));
        }else{
            return redirect('signin')
            ->with('warning','Maaf, anda diharuskan login!');
        }
    }

    public function get_pasien(Request $request)
    {
        if(session('login_user')){      
            $get_pasien = Pasien::get();

            if(count($get_pasien) != 0){
                $no = 1;
                $html='';
                $html.='
                    <table id="tlist_pasien" class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Tanggal Lahir</th>
                            <th>Aksi</th>
                            <th>Tanggal Pembuatan</th>
                            <th>Tanggal Update</th>
                        </tr>
                    </thead>
                        <tbody>
                    ';
                foreach($get_pasien as $pasien){
                    $id=$pasien->id;
                    $nik=$pasien->nik;
                    $nama=$pasien->nama;
                    $alamat=$pasien->alamat;
                    if($pasien->tgl_lahir == '' || $pasien->tgl_lahir == null){
                        $tgl_lahir='';
                    }else{
                        $tgl_lahir=date('Y-m-d',strtotime($pasien->tgl_lahir));
                    }
                    $created_at=$pasien->created_at;
                    if($pasien->updated_at == '' || $pasien->updated_at == null){
                        $updated_at='';
                    }else{
                        $updated_at=date('Y-m-d H:i:s',strtotime($pasien->updated_at));
                    }

                    $html.='
                    <tr>
                        <td>'.$no.'</td>
                        <td>'.ucwords($nik).'</td>
                        <td>'.ucwords($nama).'</td>
                        <td>'.ucwords($alamat).'</td>
                        <td>'.$tgl_lahir.'</td>
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
                            't_pasien' => $html
                        ]);
            }else{
                return json_encode([
                            'status' => 0,
                            'ket' => 'Data kosong',
                            't_pasien' => ''
                        ]);
            }
            // -------------------- End Statistik
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function fill_pasien(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->id);
            $fill_pasien = Pasien::where('id',intval($id))->first();
            if(isset($fill_pasien->nama)){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Data ditemukan',
                    'isi' => $fill_pasien,
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

    public function find_pasien(Request $request)
    {
        if(session('login_user')){
            $key = $request->q;
            $find_pasien = Pasien::where('nik','like',$key.'%')->get();
            if(count($find_pasien) != 0){
                foreach($find_pasien as $fpasien){
                    $items[] = [
                        'nik' => $fpasien->nik,
                        'nama' => $fpasien->nama,
                        'alamat' => $fpasien->alamat,
                        'tgl_lahir' => date('d F Y',strtotime($fpasien->tgl_lahir))
                    ];
                }
                
                return json_encode([
                    'status' => 1,
                    'ket' => 'Data ditemukan',
                    'items' => $items
                ]);
            }else{
                $items[] = [
                    'nik' => '',
                    'nama' => '',
                    'alamat' => '',
                    'tgl_lahir' => ''
                ];
                return json_encode([
                    'status' => 0,
                    'ket' => 'Data kosong',
                    'items' => $items
                ]);
            }
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function edit_pasien(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->pasien_id);
            $nik=$request->nik;
            $nama=ucwords($request->nama);
            $alamat=$request->alamat;
            $tgl_lahir = date('Y-m-d H:i:s',strtotime($request->tgl_lahir));

            $edit_pasien = Pasien::where('id',$id)
            ->update([
                'nik' => $nik,
                'nama' => $nama,
                'alamat' => $alamat,
                'tgl_lahir' => $tgl_lahir
            ]);

            if($edit_pasien){
                return json_encode([
                    'status' => 1,
                    'ket' => 'Berhasil update pasien '.$nama
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

    public function add_pasien(Request $request)
    {
        if(session('login_user')){
            $nik=$request->nik;
            $nama=ucwords($request->nama);
            $alamat=$request->alamat;
            $tgl_lahir = date('Y-m-d H:i:s',strtotime($request->tgl_lahir));

            $find_pasien = Pasien::where('nik',strtoupper($nik))->first();

            if(isset($find_pasien->nama)){
                return json_encode([
                    'status' => 0,
                    'ket' => 'Gagal, Pasien sudah terdaftar'
                ]);
            }else{
                $add_pasien = Pasien::create([
                    'nik' => $nik,
                    'nama' => $nama,
                    'alamat' => $alamat,
                    'tgl_lahir' => $tgl_lahir
                ]);
    
                if($add_pasien){
                    return json_encode([
                        'status' => 1,
                        'ket' => 'Berhasil tambah pasien '.$nama
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
            $delete_pasien = Pasien::where('id',$id)->delete();
            if($delete_pasien){
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
