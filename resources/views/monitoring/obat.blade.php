
@extends('layout.main')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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


        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Monitoring Obat
                </h6>
                <div  class="table-responsive">
                    <table id="t_obat" class="table">
                    </table>
                </div>
            </div>
        </div>


            
        <!-- Modal -->
        

</div>
<!-- Content End -->
@include('layout.footer')
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        showObat();
        $('#add_nik').mask("0000000000000000");
        $('#edit_nik').mask("0000000000000000");
    });

    function closeModal(modal){
        $('#'+modal).modal('hide');
    }

    function showObat(){
        $.ajax({
            url: "{{ route('mon.get.obat') }}",
            type: "POST",
            dataType: "json", 
            data:{
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
                // $('#add_akses_id').empty();
            },
            success: function (response) {
                if(response.status == 0){
					$('#t_obat').empty();
                    toastr.error(response.ket, "Gagal");
				}else if(response.status == 1){
                    $('#t_obat').empty();
                    $('#t_obat').html(response.t_obat);
                    $("#t_obat").DataTable( {
                        "bDestroy": true,
                        processing: true, //Feature control the processing indicator.
                        lengthChange: true,
                        pageLength: 10,
                        buttons: [  {
                                        extend: "excelHtml5",
                                        title: "List Obat"
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

    function add(){
        $('#ModalAdd').modal('show');
    }

    function addOneDay(date) {
        date.setDate(date.getDate() + 1);
        return date;
    }

    function edit(i){
        var val = i;
        $.ajax({
            url: "{{ route('fill.obat') }}",
            type: "POST",
            dataType: "json", 
            data:{
                id:val,
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
                $('#edit_nik').empty();
                $('#edit_nama').empty();
                $('#edit_alamat').empty();
                $('#edit_tgl_lahir').empty();
            },
            success: function (response) {
                if(response.status != 0){
                    $('#edit_obat_id').val(response.id);
                    $('#edit_nik').val(response.isi['nik']);
                    $('#edit_nama').val(response.isi['nama']);
                    $('#edit_alamat').val(response.isi['alamat']);
                    var tgl_lahir = new Date(response.isi['tgl_lahir']);
                    var tgl_lahir_new = addOneDay(tgl_lahir);
                    $('#edit_tgl_lahir').val(tgl_lahir_new.toISOString().slice(0, 10));
                    $('#edit_nik').mask("0000000000000000");
                }else{
                    toastr.error(response.ket, "Gagal");
                }
                
            },
            complete: function(){
                $('#ModalEdit').modal('show');
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
                url: "{{ route('delete.obat') }}",
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

                    showObat();
                    
                },
                error: function (response) {
                console.log('Error: '+response);
                }
            });
        }else{
            return false;
        }
    }

    $("#btn_edit").click(function(e) {
        var edit_obat_id = $('#edit_obat_id').val();
        var edit_nik = $('#edit_nik').val();
        var edit_nama = $('#edit_nama').val();
        var edit_alamat = $('#edit_alamat').val();
        var edit_tgl_lahir = $('#edit_tgl_lahir').val();
        if((edit_obat_id == '') || (edit_obat_id == null)){
            toastr.error("ID obat tidak boleh kosong", "Gagal");
        }else if((edit_nik == '') || (edit_nik == null)){
            toastr.error("NIK tidak boleh kosong", "Gagal");
        }else if((edit_nama == '') || (edit_nama == null)){
            toastr.error("Nama tidak boleh kosong", "Gagal");
        }else if((edit_alamat == '') || (edit_alamat == null)){
            toastr.error("Alamat tidak boleh kosong", "Gagal");
        }else if((edit_tgl_lahir == '') || (edit_tgl_lahir == null)){
            toastr.error("Tanggal lahir tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('edit.obat') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    obat_id:edit_obat_id,
                    nik:edit_nik,
                    nama:edit_nama,
                    alamat:edit_alamat,
                    tgl_lahir:edit_tgl_lahir,
                    "_token": "{{ csrf_token() }}"},
                beforeSend: function () {
                    $('#edit_loading').show();
                },
                success: function (response) {
                    if(response.status == 0){
                        toastr.error(response.ket, "Gagal");
                        return false;
                    }else if(response.status == 1){
                        toastr.success(response.ket, "Sukses");
                    }

                    showObat();
                },
                complete: function(){
                    $('#edit_loading').hide();
                    $("#ModalEdit").modal('hide');

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
        var add_tgl_lahir = $('#add_tgl_lahir').val();
        if((add_nik == '') || (add_nik == null)){
            toastr.error("NIK tidak boleh kosong", "Gagal");
        }else if((add_nama == '') || (add_nama == null)){
            toastr.error("Nama tidak boleh kosong", "Gagal");
        }else if((add_alamat == '') || (add_alamat == null)){
            toastr.error("Alamat tidak boleh kosong", "Gagal");
        }else if((add_tgl_lahir == '') || (add_tgl_lahir == null)){
            toastr.error("Tanggal lahir tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('add.obat') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    nik:add_nik,
                    nama:add_nama,
                    alamat:add_alamat,
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

                    showObat();
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


