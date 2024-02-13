
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
                        <p class="mb-2">Antrian Periksa Saat Ini</p>
                        <h1 class="display-3 text-success">
                            @foreach($get_antrian_now as $key => $list_antrian)
                                @if($key == 0)
                                    {{(strlen($list_antrian->nama) > 7) ? $list_antrian->no_urut.' | '.substr($list_antrian->nama,0,7).'...' : $list_antrian->no_urut.' | '.$list_antrian->nama}}
                                @endif
                            @endforeach
                        </h1>
                        <p class="mb-2">Silakan menuju ruang periksa</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-6">
                <div class="bg-light rounded align-items-center justify-content-between p-4">
                    <div class="ms-3">
                    <i class="fa fa-chart-line fa-3x text-secondary"></i>
                        <p class="mb-2">Antrian Periksa Selanjutnya</p>
                        <h1 class="display-3 text-secondary">
                            @foreach($get_antrian_now as $key => $list_antrian)
                                @if($key == 1)
                                    {{(strlen($list_antrian->nama) > 7) ? $list_antrian->no_urut.' | '.substr($list_antrian->nama,0,7).'...' : $list_antrian->no_urut.' | '.$list_antrian->nama}}
                                @endif
                            @endforeach
                        </h1>
                        <p class="mb-2">Harap menunggu</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 pt-4">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Antrian Pasien
                    <button data-toggle="modal" data-target="#ModalAdd" onclick="add()" class="btn btn-primary btn-sm px-2 py-1">
                    Daftar
                    </button>
                </h6>
                <div  class="table-responsive">
                    <table id="t_antrian" class="table">
                    </table>
                </div>
            </div>
        </div>
            
        <!-- Modal -->
        <div id="ModalAdd" class="modal hide fade" role="dialog" aria-labelledby="ModalAdd" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAddLabel">Daftar Antrian</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-floating mb-3 col-12">
                            <select class="form-control select2" id="add_nik" name="add_nik" onchange="fill_nik(this.value)" style="width:100%;">
                            </select>
                        </div>
                    </div>
                    <div id="rw_1" style="display:none;" class="row">
                        <div class="form-floating mb-3 col-6">
                            <input readonly type="text" name="add_nama" id="add_nama" class="form-control">
                            <label for="add_nama">Nama Pasien</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input readonly type="date" name="add_tgl_lahir" id="add_tgl_lahir" class="form-control">
                            <label for="add_tgl_lahir">Tanggal Lahir (Sesuai KTP)</label>
                        </div>
                    </div>
                    <div id="rw_2" style="display:none;" class="row">
                        <div class="form-floating mb-3 col-6">
                            <input readonly type="text" name="add_alamat" id="add_alamat" class="form-control">
                            <label for="add_alamat">Alamat</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_keluhan" id="add_keluhan" class="form-control">
                            <label for="add_keluhan">Keluhan</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal('ModalAdd')">Kembali</button>
                    <button type="button" class="btn btn-primary" id="btn_add">
                        <div id="add_loading" style="display:none;" class="spinner-border text-light" style="width: 1rem; height: 1rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Daftar
                    </button>
                </div>
                </div>
            </div>
        </div>

        <div id="ModalPeriksa" class="modal hide fade" role="dialog" aria-labelledby="ModalPeriksa" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalPeriksaLabel">Periksa Pasien</h5>
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
                            <input type="text" name="periksa_tindakan" id="periksa_tindakan" class="form-control">
                            <label for="periksa_tindakan">Tindakan</label>
                            <input type="button" class="btn btn-sm btn-success" id="button1" value="Tambah Tindakan" onclick="add_periksa_tindakan();"></input>
                            <input type="button" class="btn btn-sm btn-warning" id="button2" value="Tampilkan Tindakan" onclick="display_periksa_tindakan();"></input>
                            <input type="button" class="btn btn-sm btn-danger" id="button3" value="Reset Tindakan" onclick="reset_periksa_tindakan();"></input>
                            <div id="hasil_tindakan"></div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <span style="font-size:0.7rem;" for="periksa_obat">Obat</span>
                            <select style="width:100%;" class="form-control" id="periksa_obat" name="periksa_obat">
                                @foreach($get_obat as $all_obat)
                                    <option value="{{$all_obat->id.'|'.$all_obat->obat}}">{{$all_obat->obat}}</option>
                                @endforeach
                            </select>
                            <input type="button" class="btn btn-sm btn-success" id="button4" value="Tambah Obat" onclick="add_periksa_obat();"></input>
                            <input type="button" class="btn btn-sm btn-warning" id="button5" value="Tampilkan Obat" onclick="display_periksa_obat();"></input>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <span style="font-size:0.7rem;" for="periksa_obat_qty">Jumlah</span>
                            <input type="number" name="periksa_obat_qty" id="periksa_obat_qty" class="form-control">
                            <input type="button" class="btn btn-sm btn-danger" id="button6" value="Reset Obat" onclick="reset_periksa_obat();"></input>
                        </div>
                        
                        <div id="hasil_obat"></div> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal('ModalPeriksa')">Kembali</button>
                    <button type="button" class="btn btn-primary" id="btn_periksa">
                        <div id="periksa_loading" style="display:none;" class="spinner-border text-light" style="width: 1rem; height: 1rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Selesai Periksa
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

    var x = 0;
    var x2 = 0;
    var x3 = 0;
    var array_periksa_tindakan = Array();
    var array_periksa_obat = Array();
    var array_periksa_obat_qty = Array();

    
    function reset_periksa_tindakan()
    {
        array_periksa_tindakan = [];
        while (array_periksa_tindakan.length > 0) {
            a.pop();
        } // Fastest
        x = 0;
        toastr.success("Reset tindakan", "Sukses");
        display_periksa_tindakan();
    }

    function reset_periksa_obat()
    {
        array_periksa_obat = [];
        array_periksa_obat_qty = [];
        while (array_periksa_obat.length > 0) {
            a.pop();
        } // Fastest
        while (array_periksa_obat_qty.length > 0) {
            a.pop();
        } // Fastest
        x2 = 0;
        x3 = 0;
        toastr.success("Reset obat dan jumlah obat", "Sukses");
        display_periksa_obat();
    }

    function add_periksa_tindakan()
    {
        array_periksa_tindakan[x] = document.getElementById("periksa_tindakan").value;
        toastr.success("Tindakan: " + array_periksa_tindakan[x] + " telah ditambahkan pada urutan " + (x+1), "Berhasil");
        x++;
        document.getElementById("periksa_tindakan").value = "";
    }

    function display_periksa_tindakan()
    {
        var e = "<hr/>";   
        for (var y=0; y<array_periksa_tindakan.length; y++)
        {
            e += "Tindakan " + (y+1) + " = " + array_periksa_tindakan[y] + "<br/>";
        }
        document.getElementById("hasil_tindakan").innerHTML = e;
    }

    function add_periksa_obat()
    {
        array_periksa_obat[x2] = document.getElementById("periksa_obat").value;
        array_periksa_obat_qty[x3] = document.getElementById("periksa_obat_qty").value;
        toastr.success("Obat: " + array_periksa_obat[x2] + " telah ditambahkan pada urutan " + (x2+1), "Berhasil");
        x2++;
        x3++;
        document.getElementById("periksa_obat").value = "";
        document.getElementById("periksa_obat_qty").value = "";
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
            url: "{{ route('get.antrian') }}",
            type: "POST",
            dataType: "json", 
            data:{
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
            },
            success: function (response) {
                if(response.status == 0){
					$('#t_antrian').empty();
                    toastr.error(response.ket, "Gagal");
				}else if(response.status == 1){
                    $('#t_antrian').empty();
                    $('#t_antrian').html(response.t_antrian);
                    $("#t_antrian").DataTable( {
                        "bDestroy": true,
                        processing: true, //Feature control the processing indicator.
                        lengthChange: true,
                        pageLength: 10,
                        buttons: [  {
                                        extend: "excelHtml5",
                                        title: "List Antrian"
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


    function formatRepo (repo) {
        if (repo.loading) {
            return repo.text;
        }

        var $container = $(
            "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "<div class='select2-result-repository__bod'></div>" +
                "</div>" +
            "</div>" +
            "</div>"
        );

        $container.find(".select2-result-repository__title").text(repo.nik);
        $container.find(".select2-result-repository__description").text(repo.nama);
        $container.find(".select2-result-repository__bod").text(repo.tgl_lahir);

        return $container;
    }

    function formatRepoSelection (repo) {
        return repo.text;
    }

    $("#add_nik").select2({
        dropdownParent: $("#ModalAdd"),
        allowClear: true,
        language: {
        // You can find all of the options in the language files provided in the
        // build. They all must be functions that return the string that should be
        // displayed.
            inputTooShort: function () {
                return "Ketik minimal 1 huruf ..";
            },
            noResults: function () {
                return 'Tidak ada data terkait';
            },
            searching: function () {
                return 'Mencari ..';
            }
        },
        ajax: {
            url: "{{ route('find.pasien') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                q: params.term, // search term
                page: params.page
            };
            },
            processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            var select2Data = $.map(data.items, function (obj) {
                obj.id = obj.nik;
                obj.text = obj.nik+' | '+obj.nama;
                return obj;
            });



            return {
                results: select2Data
            };
            },
            cache: true
        },
        placeholder: 'NIK',
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

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
            url: "{{ route('fill.antrian') }}",
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
                    for (var x=0;x<response.get_obat.length;x++) {
                            $('#periksa_obat').append(
                                $('<option>', {
                                    value: response.get_obat[x]['id']+' | '+response.get_obat[x]['obat'],
                                    text: response.get_obat[x]['obat']
                                })
                            );
                    }
                    
                }else{
                    toastr.error(response.ket, "Gagal");
                }
                
            },
            complete: function(){
                $('#ModalPeriksa').modal('show');
            },
            error: function (response) {
            console.log('Error: '+response);
            }
        });
            
    }

    function confirm_delete(i){
        var agree = confirm("Apa anda yakin akan menghapus data yang dipilih?");
        if(agree == true){
            // alert('test');
            var val = i;
            $.ajax({
                url: "{{ route('delete.pasien') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    id:val,
                    "_token": "{{ csrf_token() }}"},
                beforeSend: function () {
                },
                success: function (response) {
                    if(response.status != 0){
                        toastr.success(response.ket, "Sukses");
                    }else{
                        toastr.error(response.ket, "Gagal");
                    }

                    showPasien();
                    
                },
                error: function (response) {
                console.log('Error: '+response);
                }
            });
        }else{
            return false;
        }
    }

    $("#btn_periksa").click(function(e) {
        var periksa_pasien_id = $('#periksa_pasien_id').val();
        var periksa_tindakan = array_periksa_tindakan;
        var periksa_obat = array_periksa_obat;
        var periksa_obat_qty = array_periksa_obat_qty;
        if((periksa_pasien_id == '') || (periksa_pasien_id == null)){
            toastr.error("ID pasien tidak boleh kosong", "Gagal");
        }else if((periksa_tindakan == '') || (periksa_tindakan == null)  || (periksa_tindakan == [])){
            toastr.error("Tindakan tidak boleh kosong", "Gagal");
        }else if((periksa_obat == '') || (periksa_obat == null)  || (periksa_obat == [])){
            toastr.error("Resep obat tidak boleh kosong", "Gagal");
        }else if((periksa_obat_qty == '') || (periksa_obat_qty == null)  || (periksa_obat_qty == [])){
            toastr.error("Jumlah obat tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('periksa.pasien') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    pasien_id:periksa_pasien_id,
                    tindakan:periksa_tindakan,
                    obat:periksa_obat,
                    obat_qty:periksa_obat_qty,
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

    $("#btn_add").click(function(e) {
        var add_nik = $('#add_nik').val();
        var add_nama = $('#add_nama').val();
        var add_alamat = $('#add_alamat').val();
        var add_keluhan = $('#add_keluhan').val();
        var add_tgl_lahir = $('#add_tgl_lahir').val();
        if((add_nik == '') || (add_nik == null)){
            toastr.error("NIK tidak boleh kosong", "Gagal");
        }else if((add_nama == '') || (add_nama == null)){
            toastr.error("Nama tidak boleh kosong", "Gagal");
        }else if((add_alamat == '') || (add_alamat == null)){
            toastr.error("Alamat tidak boleh kosong", "Gagal");
        }else if((add_tgl_lahir == '') || (add_tgl_lahir == null)){
            toastr.error("Tanggal lahir tidak boleh kosong", "Gagal");
        }else if((add_keluhan == '') || (add_keluhan == null)){
            toastr.error("Keluhan tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('daftar.antrian') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    nik:add_nik,
                    nama:add_nama,
                    alamat:add_alamat,
                    keluhan:add_keluhan,
                    tgl_lahir:add_tgl_lahir,
                    "_token": "{{ csrf_token() }}"},
                beforeSend: function () {
                    $('#add_loading').show();
                },
                success: function (response) {
                    if(response.status == 0){
                        toastr.error(response.ket, "Gagal");
                        return false;
                    }else if(response.status == 1){
                        toastr.success(response.ket, "Sukses");
                    }

                    showPasien();
                },
                complete: function(){
                    $('#add_loading').hide();
                    $("#ModalAdd").modal('hide');

                },
                error: function (response) {
                console.log('Error'+response);
                }
            });
        }
    });

</script>
@endsection


