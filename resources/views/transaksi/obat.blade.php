
@extends('layout.main')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<!-- Content Start -->
<div class="content">
    @include('layout.navbar')

    <div class="container-fluid pt-4 px-4">

        @if ($message = Session::get('success'))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>{{$message}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif


        @if ($message = Session::get('danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>{{$message}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif


        @if ($message = Session::get('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>{{$message}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif


        @if ($message = Session::get('info'))
        <div class="alert alert-secondary alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>{{$message}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif


        @if ($errors->any())
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>
            <div class="alert-text">Oops! ada kesalahan
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif


        <div class="row g-4">
            <div class="col-sm-6 col-xl-6">
                <div class="bg-light rounded align-items-center justify-content-between p-4">
                    <div class="ms-3">
                    <i class="fa fa-chart-line fa-3x text-success"></i>
                        <p class="mb-2">Antrian Resep Obat Saat Ini</p>
                        <h1 class="display-3 text-success">
                            @foreach($get_obat_now as $key => $list_obat)
                                @if($key == 0)
                                    {{(strlen($list_obat->nama) > 7) ? $list_obat->no_urut.' | '.substr($list_obat->nama,0,7).'...' : $list_obat->no_urut.' | '.$list_obat->nama}}
                                @endif
                            @endforeach
                        </h1>
                        <p class="mb-2">Silakan menuju kasir untuk pembayaran dan pengambilan obat</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-6">
                <div class="bg-light rounded align-items-center justify-content-between p-4">
                    <div class="ms-3">
                    <i class="fa fa-chart-line fa-3x text-secondary"></i>
                        <p class="mb-2">Antrian Resep Obat Selanjutnya</p>
                        <h1 class="display-3 text-secondary">
                            @foreach($get_obat_now as $key => $list_obat)
                                @if($key == 1)
                                    {{(strlen($list_obat->nama) > 7) ? $list_obat->no_urut.' | '.substr($list_obat->nama,0,7).'...' : $list_obat->no_urut.' | '.$list_obat->nama}}
                                @endif
                            @endforeach
                        </h1>
                        <p class="mb-2">Harap bersiap</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 pt-4">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Obat Pasien
                </h6>
                <div  class="table-responsive">
                    <table id="t_transaksi_obat" class="table">
                    </table>
                </div>
            </div>
        </div>
           
        <div id="ModalPeriksa" class="modal hide fade" role="dialog" aria-labelledby="ModalPeriksa" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalPeriksaLabel">Penyerahan Resep Obat Pasien</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input hidden type="text" name="periksa_pasien_id" id="periksa_pasien_id" class="form-control">
                            <input readonly type="text" name="periksa_nama" id="periksa_nama" class="form-control">
                            <label for="periksa_nama">Nama Pasien</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input readonly type="text" name="periksa_usia" id="periksa_usia" class="form-control">
                            <label for="periksa_usia">Usia</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input readonly type="text" name="periksa_alamat" id="periksa_alamat" class="form-control">
                            <label for="periksa_alamat">Alamat</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input readonly type="text" name="periksa_keluhan" id="periksa_keluhan" class="form-control">
                            <label for="periksa_keluhan">Keluhan</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-12">
                            <input type="button" class="btn btn-sm btn-warning" id="button2" value="Tampilkan Tindakan" onclick="display_periksa_tindakan();"></input>
                            <div id="hasil_tindakan"></div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input type="button" class="btn btn-sm btn-warning" id="button5" value="Tampilkan Obat" onclick="display_periksa_obat();"></input>
                        </div>
                        
                        <div id="hasil_obat"></div> 
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                        <span for="periksa_total text-danger">Total Bayar</span>
                        <h1 id="periksa_total"  class="display-5 text-danger">
                        </h1>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal('ModalPeriksa')">Kembali</button>
                    <button type="button" class="btn btn-primary" id="btn_bayar">
                        <div id="periksa_loading" style="display:none;" class="spinner-border text-light" style="width: 1rem; height: 1rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Selesai Pembayaran dan Penyerahan Obat
                    </button>
                </div>
                </div>
            </div>
        </div>

</div>
<!-- Content End -->
@include('layout.footer')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.min.js"></script>

<script>
    $(window).on('load', function() {
        toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "3000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
        };
        showPasien();
        
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    var x = 0;
    var x2 = 0;
    var x3 = 0;
    var array_periksa_tindakan = Array();
    var array_periksa_obat = Array();
    var array_periksa_obat_qty = Array();


    function display_periksa_tindakan()
    {
        var e = "<hr/>";   
        for (var y=0; y<array_periksa_tindakan.length; y++)
        {
            e += "Tindakan " + (y+1) + " = " + array_periksa_tindakan[y] + "<br/>";
        }
        document.getElementById("hasil_tindakan").innerHTML = e;
    }

    function display_periksa_obat()
    {
        var g = "<hr/>";   
        for (var y2=0; y2<array_periksa_obat.length; y2++)
        {
            g += "Obat " + (y2+1) + " = " + array_periksa_obat[y2] + "<br/>";
        }
        var f = "<hr/>";   
        for (var y3=0; y3<array_periksa_obat_qty.length; y3++)
        {
            f += "Obat " + (y3+1) + " jumlah = " + array_periksa_obat_qty[y3] + "<br/>";
        }
        document.getElementById("hasil_obat").innerHTML = g+'<hr/>'+f;
    }

    function closeModal(modal){
        $('#'+modal).modal('hide');
    }

    function showPasien(){
        $.ajax({
            url: "{{ route('get.transaksi.obat') }}",
            type: "POST",
            dataType: "json", 
            data:{
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
            },
            success: function (response) {
                if(response.status == 0){
					$('#t_transaksi_obat').empty();
                    toastr.error(response.ket, "Gagal");
				}else if(response.status == 1){
                    $('#t_transaksi_obat').empty();
                    $('#t_transaksi_obat').html(response.t_transaksi_obat);
                    $("#t_transaksi_obat").DataTable( {
                        "bDestroy": true,
                        processing: true, //Feature control the processing indicator.
                        lengthChange: true,
                        pageLength: 10,
                        buttons: [  {
                                        extend: "excelHtml5",
                                        title: "List Transaksi Obat"
                                    }
                                ],
                        "dom" : '<"row"<"col-sm-4"B><"col-sm-4"i><"col-sm-4"f>>rt<"row"<"col-sm-4"l><"col-sm-4"><"col-sm-4"p>>',
                        // "dom" : "Bifrtlip",
                        "language": {
                            "search": "Cari:",
                            "emptyTable":     "Tidak ada data!",
                            "info":           "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                            "infoEmpty":      "Menampilkan 0 - 0 dari 0 data",
                            "infoFiltered":   "(terfilterisasi dari _MAX_ jumlah data)",
                            "thousands":      ".",
                            "lengthMenu":     "Tampilkan data _MENU_",
                            "loadingRecords": "Loading...",   
                            "processing": "Sedang proses..",
                            "decimal":        ",",
                            "thousands":      "."                    
                        }
                    } );
                }

            },
            complete: function(){
            },
            error: function (data) {
            console.log('Error:'+data);
            }
        });
    }

    function fill_nik(nik){
        $.ajax({
            url: "{{ route('find.pasien') }}",
            type: "GET",
            dataType: "json", 
            data:{
                q:nik,
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
                $('#add_nama').val('');
                $('#add_alamat').val('');
                $('#add_tgl_lahir').empty();
            },
            success: function (response) {
                if(response.status != 0){
                    var obj = response.items;
                    $('#add_nama').val(obj[0].nama);
                    $('#add_alamat').val(obj[0].alamat);
                    var tgl_lahir = new Date(obj[0].tgl_lahir);
                    var tgl_lahir_new = addOneDay(tgl_lahir);
                    $('#add_tgl_lahir').val(tgl_lahir_new.toISOString().slice(0, 10));
                    $('#rw_1').show();
                    $('#rw_2').show();
                }else{
                    toastr.error(response.ket, "Gagal");
                }
                
            },
            complete: function(){
            },
            error: function (response) {
            console.log('Error: '+response);
            }
        });
    }

    function add(){
        $('#ModalAdd').modal('show');
    }

    function addOneDay(date) {
        date.setDate(date.getDate() + 1);
        return date;
    }

    function periksa(i){
        var val = i;
        $.ajax({
            url: "{{ route('fill.resep.obat') }}",
            type: "POST",
            dataType: "json", 
            data:{
                id:val,
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
                $('#periksa_keluhan').val('');
                $('#periksa_tindakan').val('');
                $('#periksa_obat').empty();
                $('#periksa_obat_qty').val('');
            },
            success: function (response) {
                if(response.status != 0){
                    $('#periksa_pasien_id').val(response.id);
                    $('#periksa_nama').val(response.isi['nama']);
                    $('#periksa_alamat').val(response.isi['alamat']);
                    $('#periksa_usia').val(response.usia);
                    $('#periksa_keluhan').val(response.isi['keluhan']);
                    $('#periksa_total').html('Rp.'+numberWithCommas(response.isi['total_harga']));
                    array_periksa_tindakan = [];
                    array_periksa_obat = [];
                    array_periksa_obat_qty = [];

                    array_periksa_tindakan = response.get_list_tindakan;
                    var gl_obat = response.get_list_obat;
                    for(var z=0;z<response.get_list_obat.length;z++){
                        array_periksa_obat.push(gl_obat[z]['obat']);
                        array_periksa_obat_qty.push(response.get_list_obat[z]['jumlah']);
                    }
                    
                }else{
                    toastr.error(response.ket, "Gagal");
                }
                
            },
            complete: function(){
                $('#ModalPeriksa').modal('show');
                display_periksa_tindakan();
                display_periksa_obat();
            },
            error: function (response) {
            console.log('Error: '+response);
            }
        });
            
    }

    $("#btn_bayar").click(function(e) {
        var periksa_pasien_id = $('#periksa_pasien_id').val();
        if((periksa_pasien_id == '') || (periksa_pasien_id == null)){
            toastr.error("ID pasien tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('bayar.obat') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    antrian_id:periksa_pasien_id,
                    "_token": "{{ csrf_token() }}"},
                beforeSend: function () {
                    $('#periksa_loading').show();
                },
                success: function (response) {
                    if(response.status == 0){
                        toastr.error(response.ket, "Gagal");
                        return false;
                    }else if(response.status == 1){
                        toastr.success(response.ket, "Sukses");
                        $("#ModalPeriksa").modal('hide');
                    }

                    showPasien();
                },
                complete: function(){
                    $('#periksa_loading').hide();
                },
                error: function (response) {
                console.log('Error'+response);
                }
            });
        }
	});


</script>
@endsection


