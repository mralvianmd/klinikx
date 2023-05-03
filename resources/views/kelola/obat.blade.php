
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
                <h6 class="mb-4">List Obat
                    <button data-toggle="modal" data-target="#ModalAdd" onclick="add()" class="btn btn-primary btn-sm px-2 py-1">
                    Tambah
                    </button>
                </h6>
                <div  class="table-responsive">
                    <table id="t_obat" class="table">
                    </table>
                </div>
            </div>
        </div>


            
        <!-- Modal -->
        <div id="ModalAdd" class="modal hide fade" role="dialog" aria-labelledby="ModalAdd" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAddLabel">Tambah Obat</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_obat" id="add_obat" class="form-control">
                            <label for="add_obat">Nama Obat</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_jumlah" id="add_jumlah" class="form-control">
                            <label for="add_jumlah">Jumlah</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_harga" id="add_harga" class="form-control">
                            <label for="add_harga">Harga</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal('ModalAdd')">Kembali</button>
                    <button type="button" class="btn btn-primary" id="btn_add">
                        <div id="add_loading" style="display:none;" class="spinner-border text-light" style="width: 1rem; height: 1rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Tambah
                    </button>
                </div>
                </div>
            </div>
        </div>

        <div id="ModalEdit" class="modal hide fade" role="dialog" aria-labelledby="ModalEdit" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalEditLabel">Edit Obat</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input hidden type="text" name="edit_obat_id" id="edit_obat_id" class="form-control">
                            <input type="text" name="edit_obat" id="edit_obat" class="form-control">
                            <label for="edit_obat">Nama Obat</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="edit_jumlah" id="edit_jumlah" class="form-control">
                            <label for="edit_jumlah">Jumlah</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="edit_harga" id="edit_harga" class="form-control">
                            <label for="edit_harga">Harga</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal('ModalEdit')">Kembali</button>
                    <button type="button" class="btn btn-primary" id="btn_edit">
                        <div id="edit_loading" style="display:none;" class="spinner-border text-light" style="width: 1rem; height: 1rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Edit
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
        $('#add_harga').mask("#,##0", {reverse: true});
        $('#add_jumlah').mask("#,##0", {reverse: true});
        $('#edit_harga').mask("#,##0", {reverse: true});
        $('#edit_jumlah').mask("#,##0", {reverse: true});
    });

    function closeModal(modal){
        $('#'+modal).modal('hide');
    }

    function showObat(){
        $.ajax({
            url: "{{ route('get.obat') }}",
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
                $('#edit_obat').empty();
                $('#edit_jumlah').empty();
                $('#edit_harga').empty();
            },
            success: function (response) {
                if(response.status != 0){
                    $('#edit_obat_id').val(response.id);
                    $('#edit_obat').val(response.isi['obat']);
                    $('#edit_jumlah').val(response.isi['qty']);
                    $('#edit_harga').val(response.isi['harga']);
                    $('#edit_harga').mask("#,##0", {reverse: true});
                    $('#edit_jumlah').mask("#,##0", {reverse: true});
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
        var edit_obat = $('#edit_obat').val();
        var edit_jumlah = $('#edit_jumlah').val();
        var edit_harga = $('#edit_harga').val();
        if((edit_obat_id == '') || (edit_obat_id == null)){
            toastr.error("ID obat tidak boleh kosong", "Gagal");
        }else if((edit_obat == '') || (edit_obat == null)){
            toastr.error("Nama obat tidak boleh kosong", "Gagal");
        }else if((edit_jumlah == '') || (edit_jumlah == null) || (edit_jumlah == '0')){
            toastr.error("Jumlah tidak boleh kosong atau 0", "Gagal");
        }else if((edit_harga == '') || (edit_harga == null)){
            toastr.error("Harga tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('edit.obat') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    obat_id:edit_obat_id,
                    obat:edit_obat,
                    jumlah:edit_jumlah,
                    harga:edit_harga,
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
        var add_obat = $('#add_obat').val();
        var add_jumlah = $('#add_jumlah').val();
        var add_harga = $('#add_harga').val();
        if((add_obat == '') || (add_obat == null)){
            toastr.error("Nama obat tidak boleh kosong", "Gagal");
        }else if((add_jumlah == '') || (add_jumlah == null) || (add_jumlah == '0')){
            toastr.error("Jumlah tidak boleh kosong atau 0", "Gagal");
        }else if((add_harga == '') || (add_harga == null)){
            toastr.error("Harga tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('add.obat') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    obat:add_obat,
                    jumlah:add_jumlah,
                    harga:add_harga,
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


