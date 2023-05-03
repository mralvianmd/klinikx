<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hakakses;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use DB;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index_obat()
    {
        if(session('login_user')){
            $page_title = 'Monitoring Obat';
            $page_description = 'Halaman monitoring obat';
            $get_obat = Obat::where('qty','>',0)->get();
            return view('monitoring.obat',compact('page_title',
            'page_description',
            'get_obat'
            ));
        }else{
            return redirect('signin')
            ->with('warning','Maaf, anda diharuskan login!');
        }
    }

    public function get_obat(Request $request)
    {
        if(session('login_user')){      
            $get_obat = DB::table('obats as a')
            ->leftJoin('riwayat_obats as b','a.id','=','b.obat_id')
            ->select(
                'a.id',
                'a.obat',
                'a.harga',
                'a.qty',
                'b.qty_in',
                'b.qty_out',
                'b.created_at',
                'b.updated_at',
            )
            ->orderBy('b.created_at','desc')
            ->get();

            if(count($get_obat) != 0){
                $no = 1;
                $html='';
                $html.='
                    <table id="tlist_obat" class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Obat</th>
                            <th>Stok</th>
                            <th>Stok Masuk</th>
                            <th>Stok Keluar</th>
                            <th>Harga</th>
                            <th>Tanggal Update</th>
                        </tr>
                    </thead>
                        <tbody>
                    ';
                foreach($get_obat as $obat){
                    $id=$obat->id;
                    $nama_obat=$obat->obat;
                    $jumlah=$obat->qty;
                    $jumlah_in=number_format($obat->qty_in,0,'.',',');
                    $jumlah_out=number_format($obat->qty_out,0,'.',',');
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
                        <td>'.($jumlah).'</td>
                        <td>'.($jumlah_in).'</td>
                        <td>'.($jumlah_out).'</td>
                        <td>'.$harga.'</td>
                        <td>'.date('Y-m-d H:i:s',strtotime($created_at)).'</td>
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

    public function index_pasien(Request $request)
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

}
