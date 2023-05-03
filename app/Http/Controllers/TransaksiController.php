<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Pasien;
use App\Models\RiwayatObat;
use App\Models\RiwayatPasien;
use App\Models\User;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use DateTime;
use Str;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(session('login_user')){
            $page_title = 'Antrian';
            $page_description = 'Halaman antrian';
            $get_obat = Obat::where('qty','>',0)->get();
            $get_antrian_now = DB::table('antrians as a')
            ->rightJoin('pasiens as b','a.pasien_id','=','b.id')
            ->join('referensi_statuses as c','a.status_antrian','=','c.kode')
            ->select(
                'a.id',
                'a.no_urut',
                'a.keluhan',
                'a.status_antrian',
                'a.created_at',
                'a.updated_at',
                'b.nama',
                'b.alamat',
                'b.tgl_lahir',
                'c.deskripsi as deskripsi_status'
            )
            ->whereDate('a.created_at',date('Y-m-d'))
            ->where('status_antrian','AN0')
            ->orderBy('a.no_urut','asc')
            ->limit(2)
            ->get();
            return view('transaksi.antrian',compact('page_title',
            'page_description',
            'get_antrian_now',
            'get_obat'
            ));
        }else{
            return redirect('signin')
            ->with('warning','Maaf, anda diharuskan login!');
        }
    }

    public function index_obat()
    {
        if(session('login_user')){
            $page_title = 'Transaksi Obat';
            $page_description = 'Halaman transaksi obat';
            $get_obat = Obat::where('qty','>',0)->get();
            $get_obat_now = DB::table('antrians as a')
            ->rightJoin('pasiens as b','a.pasien_id','=','b.id')
            ->join('referensi_statuses as c','a.status_antrian','=','c.kode')
            ->select(
                'a.id',
                'a.no_urut',
                'a.keluhan',
                'a.status_antrian',
                'a.created_at',
                'a.updated_at',
                'b.nama',
                'b.alamat',
                'b.tgl_lahir',
                'c.deskripsi as deskripsi_status'
            )
            ->whereDate('a.created_at',date('Y-m-d'))
            ->where('status_antrian','AN1')
            ->orderBy('a.no_urut','asc')
            ->limit(2)
            ->get();
            return view('transaksi.obat',compact('page_title',
            'page_description',
            'get_obat_now',
            'get_obat'
            ));
        }else{
            return redirect('signin')
            ->with('warning','Maaf, anda diharuskan login!');
        }
    }

    public function get_antrian(Request $request)
    {
        if(session('login_user')){      
            $get_antrian = DB::table('antrians as a')
            ->rightJoin('pasiens as b','a.pasien_id','=','b.id')
            ->join('referensi_statuses as c','a.status_antrian','=','c.kode')
            ->select(
                'a.id',
                'a.no_urut',
                'a.keluhan',
                'a.status_antrian',
                'a.created_at',
                'a.updated_at',
                'b.nama',
                'b.alamat',
                'b.tgl_lahir',
                'c.deskripsi as deskripsi_status'
            )
            ->whereDate('a.created_at',date('Y-m-d'))
            ->where('status_antrian','AN0')
            ->orderBy('a.no_urut','asc')
            ->get();

            if(count($get_antrian) != 0){
                $no = 1;
                $html='';
                $html.='
                    <table id="tlist_pasien" class="table">
                    <thead>
                        <tr>
                            <th>Nomor Antrian</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Usia</th>
                            <th>Keluhan</th>
                            <th>Status Antrian</th>
                            <th>Aksi</th>
                            <th>Tanggal Pembuatan</th>
                            <th>Tanggal Update</th>
                        </tr>
                    </thead>
                        <tbody>
                    ';
                foreach($get_antrian as $antrian){
                    $id=$antrian->id;
                    $no_urut=$antrian->no_urut;
                    $keluhan=$antrian->keluhan;
                    $status_antrian=$antrian->status_antrian;
                    $deskripsi_status=$antrian->deskripsi_status;
                    $nama=$antrian->nama;
                    $alamat=$antrian->alamat;
                    if($antrian->tgl_lahir == '' || $antrian->tgl_lahir == null){
                        $tgl_lahir='';
                        $usia = '';
                    }else{
                        $tgl_lahir=date('Y-m-d',strtotime($antrian->tgl_lahir));
                        $date = new DateTime($tgl_lahir);
                        $now = new DateTime();
                        $interval = $now->diff($date);
                        $usia = $interval->y;
                    }
                    $created_at=$antrian->created_at;
                    if($antrian->updated_at == '' || $antrian->updated_at == null){
                        $updated_at='';
                    }else{
                        $updated_at=date('Y-m-d H:i:s',strtotime($antrian->updated_at));
                    }

                    $html.='
                    <tr>
                        <td>'.($no_urut).'</td>
                        <td>'.ucwords($nama).'</td>
                        <td>'.ucwords($alamat).'</td>
                        <td>'.$usia.' tahun</td>
                        <td>'.$keluhan.'</td>
                        <td>'.$deskripsi_status.'</td>
                        <td>';
                            if(($status_antrian == 'AN0') && (session('akses_id') == 'DR01') && ($no == 1)){
                                $html.='                                
                                <button data-toggle="modal" data-target="#ModalPeriksa" onclick=periksa('."'".Crypt::encryptString($id)."'".') class="btn btn-success btn-sm px-2 py-1">
                                Periksa
                                </button>
                                ';
                            }else{
                                $html.='                                
                                <button disabled data-toggle="modal" data-target="#ModalPeriksa" onclick=periksa('."'".Crypt::encryptString($id)."'".') class="btn btn-secondary btn-sm px-2 py-1">
                                Periksa
                                </button>
                                ';
                            }
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
                            't_antrian' => $html
                        ]);
            }else{
                return json_encode([
                            'status' => 0,
                            'ket' => 'Data kosong',
                            't_antrian' => ''
                        ]);
            }
            // -------------------- End Statistik
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function get_transaksi_obat(Request $request)
    {
        if(session('login_user')){      
            $get_antrian = DB::table('antrians as a')
            ->rightJoin('pasiens as b','a.pasien_id','=','b.id')
            ->join('referensi_statuses as c','a.status_antrian','=','c.kode')
            ->select(
                'a.id',
                'a.no_urut',
                'a.keluhan',
                'a.status_antrian',
                'a.created_at',
                'a.updated_at',
                'b.nama',
                'b.alamat',
                'b.tgl_lahir',
                'c.deskripsi as deskripsi_status'
            )
            ->whereDate('a.created_at',date('Y-m-d'))
            ->where('status_antrian','AN1')
            ->orderBy('a.no_urut','asc')
            ->get();

            if(count($get_antrian) != 0){
                $no = 1;
                $html='';
                $html.='
                    <table id="tlist_transaksi_obat" class="table">
                    <thead>
                        <tr>
                            <th>Nomor Antrian</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Usia</th>
                            <th>Keluhan</th>
                            <th>Status Antrian</th>
                            <th>Aksi</th>
                            <th>Tanggal Pembuatan</th>
                            <th>Tanggal Update</th>
                        </tr>
                    </thead>
                        <tbody>
                    ';
                foreach($get_antrian as $antrian){
                    $id=$antrian->id;
                    $no_urut=$antrian->no_urut;
                    $keluhan=$antrian->keluhan;
                    $status_antrian=$antrian->status_antrian;
                    $deskripsi_status=$antrian->deskripsi_status;
                    $nama=$antrian->nama;
                    $alamat=$antrian->alamat;
                    if($antrian->tgl_lahir == '' || $antrian->tgl_lahir == null){
                        $tgl_lahir='';
                        $usia = '';
                    }else{
                        $tgl_lahir=date('Y-m-d',strtotime($antrian->tgl_lahir));
                        $date = new DateTime($tgl_lahir);
                        $now = new DateTime();
                        $interval = $now->diff($date);
                        $usia = $interval->y;
                    }
                    $created_at=$antrian->created_at;
                    if($antrian->updated_at == '' || $antrian->updated_at == null){
                        $updated_at='';
                    }else{
                        $updated_at=date('Y-m-d H:i:s',strtotime($antrian->updated_at));
                    }

                    $html.='
                    <tr>
                        <td>'.($no_urut).'</td>
                        <td>'.ucwords($nama).'</td>
                        <td>'.ucwords($alamat).'</td>
                        <td>'.$usia.' tahun</td>
                        <td>'.$keluhan.'</td>
                        <td>'.$deskripsi_status.'</td>
                        <td>';
                            if(($status_antrian == 'AN1') && ((session('akses_id') == 'AP01') || (session('akses_id') == 'ADM')) && ($no == 1)){
                                $html.='                                
                                <button data-toggle="modal" data-target="#ModalPeriksa" onclick=periksa('."'".Crypt::encryptString($id)."'".') class="btn btn-success btn-sm px-2 py-1">
                                Pembayaran dan Penyerahan Resep Obat
                                </button>
                                ';
                            }else{
                                $html.='                                
                                <button disabled data-toggle="modal" data-target="#ModalPeriksa" onclick=periksa('."'".Crypt::encryptString($id)."'".') class="btn btn-secondary btn-sm px-2 py-1">
                                Pembayaran dan Penyerahan Resep Obat
                                </button>
                                ';
                            }
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
                            't_transaksi_obat' => $html
                        ]);
            }else{
                return json_encode([
                            'status' => 0,
                            'ket' => 'Data kosong',
                            't_transaksi_obat' => ''
                        ]);
            }
            // -------------------- End Statistik
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function daftar_antrian(Request $request)
    {
        if(session('login_user')){
            $today = strval(date('Y-m-d'));
            $nik=$request->nik;
            $keluhan=$request->keluhan;
            $find_pasien = Pasien::where('nik',strtoupper($nik))->first();
            $find_last_antrian = Antrian::whereDate('created_at',date('Y-m-d'))->orderBy('id','desc')->first();
            $find_AN0_self_antrian = Antrian::whereDate('created_at',date('Y-m-d'))
            ->where('pasien_id',$find_pasien->id)
            ->whereIn('status_antrian',['AN0','AN1'])
            ->orderBy('id','desc')
            ->first();

            if(isset($find_AN0_self_antrian->no_urut)){
                return json_encode([
                    'status' => 0,
                    'ket' => 'Pasien sudah terdaftar dan dalam proses </br> Silakan menunggu hingga proses periksa selesai lalu daftar antrian kembali'
                ]);
            }
            if(isset($find_pasien->nama)){
                if(isset($find_last_antrian->no_urut)){
                    $no_urut = intval($find_last_antrian->no_urut)+1;
                }else{
                    $no_urut = 1;
                }

                DB::beginTransaction();
                try {
                    $daftar_antrian = DB::table('antrians')->insert([
                        'no_urut' => $no_urut,
                        'pasien_id' => $find_pasien->id,
                        'keluhan' => $keluhan,
                        'status_antrian' => 'AN0'
                    ]);
                    DB::commit();
                    return json_encode(['status' => 1, 
                    'ket' => 'Berhasil daftar antrian pasien '.$find_pasien->nama]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return json_encode(['status' => 0, 
                    'ket' => 'Maaf, terdapat kesalahan! </br>'.$e->getMessage()]);
                }
                
            }else{
                return json_encode([
                    'status' => 0,
                    'ket' => 'Maaf, Pasien belum terdaftar'
                ]);
            }
            
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }

    public function fill_antrian(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->id);
            $get_obat = Obat::where('qty','>',0)->get();
            $fill_antrian = DB::table('antrians as a')
            ->rightJoin('pasiens as b','a.pasien_id','=','b.id')
            ->join('referensi_statuses as c','a.status_antrian','=','c.kode')
            ->select(
                'a.id',
                'a.no_urut',
                'a.keluhan',
                'a.status_antrian',
                'a.created_at',
                'a.updated_at',
                'b.nama',
                'b.alamat',
                'b.tgl_lahir',
                'c.deskripsi as deskripsi_status'
            )
            ->whereDate('a.created_at',date('Y-m-d'))
            ->where('a.id',intval($id))
            ->orderBy('a.no_urut','asc')
            ->first();

            if(isset($fill_antrian->nama)){
                $tgl_lahir=date('Y-m-d',strtotime($fill_antrian->tgl_lahir));
                $date = new DateTime($tgl_lahir);
                $now = new DateTime();
                $interval = $now->diff($date);
                $usia = $interval->y;
                
                return json_encode([
                    'status' => 1,
                    'ket' => 'Data ditemukan',
                    'isi' => $fill_antrian,
                    'id' => $request->id,
                    'usia' => $usia,
                    'get_obat' => $get_obat
                    
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

    public function fill_resep_obat(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->id);
            $fill_antrian = DB::table('antrians as a')
            ->rightJoin('pasiens as b','a.pasien_id','=','b.id')
            ->join('referensi_statuses as c','a.status_antrian','=','c.kode')
            ->join('transaksis as d','a.id','=','d.user_id')
            ->join('riwayat_pasiens as e','d.riwayat_id','=','e.id')
            ->select(
                'a.id',
                'a.no_urut',
                'a.keluhan',
                'a.status_antrian',
                'a.created_at',
                'a.updated_at',
                'b.nama',
                'b.alamat',
                'b.tgl_lahir',
                'c.deskripsi as deskripsi_status',
                'd.total_harga',
                'e.tindakan_id',
                'e.obat_id'
            )
            ->whereDate('a.created_at',date('Y-m-d'))
            ->whereDate('d.created_at',date('Y-m-d'))
            ->whereDate('e.created_at',date('Y-m-d'))
            ->where('a.id',intval($id))
            ->orderBy('a.no_urut','asc')
            ->first();

            if(isset($fill_antrian->nama)){
                $tgl_lahir=date('Y-m-d',strtotime($fill_antrian->tgl_lahir));
                $date = new DateTime($tgl_lahir);
                $now = new DateTime();
                $interval = $now->diff($date);
                $usia = $interval->y;
                $list_obat = json_decode($fill_antrian->obat_id);
                $list_tindakan = json_decode($fill_antrian->tindakan_id);
                $get_jumlah_obat = RiwayatObat::where('user_id',$fill_antrian->id)->get();
                foreach($get_jumlah_obat as $jml_obat){
                    $get_jml_obat[] = $jml_obat->qty_out;
                }
                $get_obat = Obat::whereIn('id',$list_obat)->get();
                foreach($get_obat as $key => $all_obat){
                    $val_obat = $all_obat->id.' | '.$all_obat->obat;
                    $get_list_obat[] = [
                        'jumlah' => $get_jml_obat[$key],
                        'obat' => $val_obat
                    ];
                }
                return json_encode([
                    'status' => 1,
                    'ket' => 'Data ditemukan',
                    'isi' => $fill_antrian,
                    'id' => $request->id,
                    'usia' => $usia,
                    'get_list_obat' => $get_list_obat,
                    'get_list_tindakan' => $list_tindakan
                    
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

    public function bayar_obat(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->antrian_id);
            $fill_antrian = DB::table('antrians as a')
            ->rightJoin('pasiens as b','a.pasien_id','=','b.id')
            ->join('referensi_statuses as c','a.status_antrian','=','c.kode')
            ->select(
                'a.id',
                'a.no_urut',
                'a.keluhan',
                'a.status_antrian',
                'a.created_at',
                'a.updated_at',
                'b.nama',
                'b.alamat',
                'b.tgl_lahir',
                'c.deskripsi as deskripsi_status'
            )
            ->whereDate('a.created_at',date('Y-m-d'))
            ->where('a.id',intval($id))
            ->orderBy('a.no_urut','asc')
            ->first();

            if(isset($fill_antrian->nama)){
                DB::beginTransaction();
                try {
                    $update_transaksi = DB::table('transaksis')->where('user_id',$id)->update([
                        'status_bayar' => 'L1'
                    ]);
                    $update_antrian = DB::table('antrians')->where('id',$id)->update([
                        'status_antrian' => 'AN2'
                    ]);
                    DB::commit();
                    $ket = 'Sukses melakukan transaksi';
                    return json_encode([
                        'status' => 1,
                        'ket' => $ket,
                    ]);
                } catch (\Exception $e) {
                    DB::rollback();
                    $ket = 'Maaf, terdapat kesalahan! </br>'.$e->getMessage();
                    return json_encode([
                        'status' => 0,
                        'ket' => $ket,
                    ]);
                }
                
                

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

    public function periksa_pasien(Request $request)
    {
        if(session('login_user')){
            $id = Crypt::decryptString($request->pasien_id);
            $tindakan = $request->tindakan;
            $obat = $request->obat;
            $obat_qty = $request->obat_qty;
            
            $pattern = '/[|]/';
            for($i=0;$i<count($obat);$i++){
                $store[$i] = preg_split($pattern, $obat[$i], -1);
                $store_qty[$i] = preg_split($pattern, $obat_qty[$i], -1);
                $select_obat_id[$i] = intval($store[$i][0]);
                $select_obat_id_string[$i] = strval($store[$i][0]);
                $select_obat_qty[$i] = intval($store_qty[$i][0]);
            }

            $total_harga_obat = 0;
            for($i=0;$i<count($select_obat_id);$i++){
                $get_obat = Obat::where('id',$select_obat_id[$i])->first();
                if(isset($get_obat->id)){
                    $total_harga_obat = $total_harga_obat + (($select_obat_qty[$i]) * $get_obat->harga);
                    if($get_obat->qty < $select_obat_qty[$i]){
                        return json_encode([
                            'status' => 0,
                            'ket' => 'Terdapat obat yang sudah habis ('.$get_obat->obat.') </>Sisa obat: '.$get_obat->qty,
                            'isi' => ''
                        ]);
                    }
                }
            }

            // Tindakan
            for($i=0;$i<count($tindakan);$i++){
                DB::beginTransaction();
                try {
                    $insert_tindakan = DB::table('tindakans')->insert([
                        'tindakan' => $tindakan[$i],
                        'user_id' => $id
                    ]);
                    DB::commit();
                    $ket_tindakan[] = 'Sukses insert tindakan '.$tindakan[$i];
                } catch (\Exception $e) {
                    DB::rollback();
                    $ket_tindakan[] = 'Maaf, terdapat kesalahan! </br>'.$e->getMessage();
                }
            }

            // Obat
            for($i=0;$i<count($select_obat_id);$i++){
                $get_obat = Obat::where('id',$select_obat_id[$i])->first();
                DB::beginTransaction();
                try {
                    $insert_riwayat_obat = DB::table('riwayat_obats')->insert([
                        'obat_id' => $select_obat_id[$i],
                        'qty_out' => $select_obat_qty[$i],
                        'user_id' => $id
                    ]);
                    $update_stok_obat = DB::table('obats')->where('id',$select_obat_id[$i])->update([
                        'qty' => (intval($get_obat->qty)-intval($select_obat_qty[$i]))
                    ]);
                    DB::commit();
                    $ket_obat[] = 'Sukses update obat ID '.$select_obat_id[$i];
                } catch (\Exception $e) {
                    DB::rollback();
                    $ket_obat[] = 'Maaf, terdapat kesalahan! </br>'.$e->getMessage();
                }
            }

            // Transaksi
            $genCode = 'TR'.strval(date('YmdHi')).Str::upper(Str::random(5));
            $insert_riwayat_pasien = RiwayatPasien::create([
                'tindakan_id' => json_encode($tindakan),
                'obat_id' => json_encode($select_obat_id_string),
                'created_by' => intval(session('authid'))
            ]);
            if(isset($insert_riwayat_pasien->id)){
                DB::beginTransaction();
                try {
                    $insert_transaksi = DB::table('transaksis')->insert([
                        'kode_transaksi' => $genCode,
                        'riwayat_id' => $insert_riwayat_pasien->id,
                        'total_harga' => $total_harga_obat,
                        'user_id' => $id,
                        'status_bayar' => 'L0'
                    ]);
                    $update_antrian = DB::table('antrians')->where('id',$id)->update([
                        'status_antrian' => 'AN1'
                    ]);
                    DB::commit();
                    $ket_transaksi[] = 'Sukses tambah transaksi '.$genCode;
                } catch (\Exception $e) {
                    DB::rollback();
                    $ket_transaksi[] = 'Maaf, terdapat kesalahan! </br>'.$e->getMessage();
                }
            }else{
                return json_encode([
                    'status' => 0,
                    'ket' => 'Gagal insert riwayat pasien, transaksi belum diproses'
                ]);
            }
            
            
                return json_encode([
                    'status' => 1,
                    'ket' => $ket_transaksi[0]
                    
                ]);
   
        }else{
            return json_encode(['status' => 0, 'ket' => 'Maaf, anda diharuskan login!']);
        }
    }
}
