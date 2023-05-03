<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Validator;
use Str;
use App\Models\Menu;



class PagesController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth','verified']);
    }

    public function generateCode(Request $request)
    {
        if(!empty($request->value)){
            $val = $request->value;
            $genCode = $val.strval(date('Ymd')).Str::upper(Str::random(5));
            return json_encode([
                'status' => 1,
                'ket' => 'Berhasil generate code',
                'code' => $genCode]);
        }else{
            return json_encode([
                'status' => 0,
                'ket' => 'Gagal generate code',
                'code' => '']);
        }
       
    }

    public function index(Request $request)
    {
        if(session('login_user')){
            $page_title = 'Dashboard';
            $page_description = 'Halaman utama';
            $pasien_terdaftar = DB::table('pasiens')
            ->count();
            $pasien_antrian_today = DB::table('antrians')
            ->whereIn('status_antrian',['AN0','AN1'])
            ->whereDate('created_at',date('Y-m-d'))
            ->count();
            $pasien_antrian_selesai = DB::table('antrians')
            ->where('status_antrian','AN2')
            ->whereDate('created_at',date('Y-m-d'))
            ->count();
            $pasien_antrian_aktif = DB::table('antrians as a')
            ->join('pasiens as b','a.pasien_id','=','b.id')
            ->select('a.pasien_id')
            ->groupBy('a.pasien_id')
            ->get();

            $rekap_obat = DB::table('riwayat_obats as a')
            ->join('obats as b','a.obat_id','=','b.id')
            ->select(
                'a.obat_id',
                'b.obat',
                DB::raw('sum(a.qty_out) as qty_out'),
                DB::raw('sum(a.qty_in) as qty_in'),
                DB::raw('sum(b.qty) as qty'),
                )
            ->groupBy('a.obat_id','b.obat')
            ->orderBy('b.qty','desc')
            ->limit(10)
            ->get();

            foreach($rekap_obat as $rkp_obat){
                $qty_in[] = $rkp_obat->qty_in;
                $qty_out[] = $rkp_obat->qty_out;
                $qty_stock[] = $rkp_obat->qty;
                $obat[] = $rkp_obat->obat;
                $obat_id[] = 'Kode Obat '.$rkp_obat->obat_id;
                $warna[] = "rgba(0, 156, 255, .7)";
                $chart_obat = array(
                    'qty_in' => $qty_in,
                    'qty_out' => $qty_out,
                    'qty_stock' => $qty_stock,
                    'obat' => $obat,
                    'obat_id' => $obat_id
                );
            }
            $label = array('In','Out','Stock');
            for($i=0;$i<count($label);$i++){
                if($i==0){
                    $label_chart_obat[] = array(
                        'data' => $qty_in,
                        'label' => $label[$i],
                        'backgroundColor' => "rgba(0, 156, 255, .7)"
                    );
                }elseif($i==1){
                    $label_chart_obat[] = array(
                        'data' => $qty_out,
                        'label' => $label[$i],
                        'backgroundColor' => "rgba(0, 156, 255, .5)"
                    );
                }else{
                    $label_chart_obat[] = array(
                        'data' => $qty_stock,
                        'label' => $label[$i],
                        'backgroundColor' => "rgba(0, 156, 255, .3)"
                    );
                }

            }


            $convert_chart_obat = json_encode($chart_obat);
            $convert_label_chart_obat = json_encode($label_chart_obat);
            // dd($convert_label_chart_obat);
            $count_pasien_antrian_aktif = count($pasien_antrian_aktif);
            return view('index',compact('page_title',
            'page_description',
            'pasien_terdaftar',
            'pasien_antrian_today',
            'pasien_antrian_selesai',
            'count_pasien_antrian_aktif',
            'convert_chart_obat',
            'convert_label_chart_obat'
            ));
        }else{
            return redirect('signin')
            ->with('warning','Maaf, anda diharuskan login!');
        }
    }
}
