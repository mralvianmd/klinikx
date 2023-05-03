
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
                <h6 class="mb-4">List Menu
                    <button data-toggle="modal" data-target="#ModalAdd" onclick="add()" class="btn btn-primary btn-sm px-2 py-1">
                    Tambah
                    </button>
                </h6>
                <div  class="table-responsive">
                    <table id="t_menu" class="table">
                    </table>
                </div>
            </div>
        </div>


            
        <!-- Modal -->
        <div id="ModalAdd" class="modal hide fade" role="dialog" aria-labelledby="ModalAdd" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAddLabel">Tambah Menu</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_menu_id" id="add_menu_id" class="form-control">
                            <label for="add_menu_id">Menu ID</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_deskripsi" id="add_deskripsi" class="form-control">
                            <label for="add_deskripsi">Deskripsi</label>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_route" id="add_route" class="form-control">
                            <label for="add_route">Route</label>
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
                    <h5 class="modal-title" id="ModalEditLabel">Edit Menu</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input hidden type="text" name="edit_menu_id" id="edit_menu_id" class="form-control">
                            <input readonly type="text" name="edit_menu" id="edit_menu" class="form-control">
                            <label for="edit_menu_id">Menu ID</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="edit_deskripsi" id="edit_deskripsi" class="form-control">
                            <label for="edit_deskripsi">Deskripsi</label>
                        </div>
                    </div>
                    <div class="row">
                    <div class="form-floating mb-3 col-6">
                            <input type="text" name="edit_route" id="edit_route" class="form-control">
                            <label for="edit_route">Route</label>
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
        showMenu();
    });

    function closeModal(modal){
        $('#'+modal).modal('hide');
    }

    function showMenu(){
        $.ajax({
            url: "{{ route('get.menu') }}",
            type: "POST",
            dataType: "json", 
            data:{
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
            },
            success: function (response) {
                if(response.status == 0){
					$('#t_menu').empty();
                    toastr.error(response.ket, "Gagal");
				}else if(response.status == 1){
                    $('#t_menu').empty();
                    $('#t_menu').html(response.t_menu);
                    $("#t_menu").DataTable( {
                        "bDestroy": true,
                        processing: true, //Feature control the processing indicator.
                        lengthChange: true,
                        pageLength: 10,
                        buttons: [  {
                                        extend: "excelHtml5",
                                        title: "List Menu"
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
            url: "{{ route('fill.menu') }}",
            type: "POST",
            dataType: "json", 
            data:{
                id:val,
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
                $('#edit_deskripsi').empty();
                $('#edit_route').empty();
            },
            success: function (response) {
                if(response.status != 0){
                    $('#edit_menu_id').val(response.id);
                    $('#edit_menu').val(response.isi['menu_id']);
                    $('#edit_deskripsi').val(response.isi['deskripsi']);
                    $('#edit_route').val(response.isi['route']);
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
            var val = i;
            $.ajax({
                url: "{{ route('delete.menu') }}",
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

                    showMenu();
                    
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
        var edit_menu_id = $('#edit_menu_id').val();
        var edit_deskripsi = $('#edit_deskripsi').val();
        var edit_route = $('#edit_route').val();
        if((edit_menu_id == '') || (edit_menu_id == null)){
            toastr.error("ID menu tidak boleh kosong", "Gagal");
        }else if((edit_deskripsi == '') || (edit_deskripsi == null)){
            toastr.error("Deskripsi tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('edit.menu') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    menu_id:edit_menu_id,
                    deskripsi:edit_deskripsi,
                    route:edit_route,
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

                    showMenu();
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
        var add_menu_id = $('#add_menu_id').val();
        var add_deskripsi = $('#add_deskripsi').val();
        var add_route = $('#add_route').val();
        if((add_menu_id == '') || (add_menu_id == null)){
            toastr.error("ID menu tidak boleh kosong", "Gagal");
        }else if((add_deskripsi == '') || (add_deskripsi == null)){
            toastr.error("Deskripsi tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('add.menu') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    menu_id:add_menu_id,
                    deskripsi:add_deskripsi,
                    route:add_route,
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

                    showMenu();
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


