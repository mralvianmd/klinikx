
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
                <h6 class="mb-4">List Hak Akses
                    <button data-toggle="modal" data-target="#ModalAdd" onclick="add()" class="btn btn-primary btn-sm px-2 py-1">
                    Tambah
                    </button>
                </h6>
                <div  class="table-responsive">
                    <table id="t_hakakses" class="table">
                    </table>
                </div>
            </div>
        </div>


            
        <!-- Modal -->
        <div id="ModalAdd" class="modal hide fade" role="dialog" aria-labelledby="ModalAdd" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAddLabel">Tambah Hak Akses</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_akses_id" id="add_akses_id" class="form-control">
                            <label for="add_akses_id">Akses ID</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_akses" id="add_akses" class="form-control">
                            <label for="add_akses_id">Nama Akses</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_deskripsi" id="add_deskripsi" class="form-control">
                            <label for="add_deskripsi">Deskripsi</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <span style="font-size:0.7rem;" for="add_menu_id">Akses Menu</span>
                            <select style="width:100%;" class="form-control select2" multiple="multiple" id="add_menu_id" name="add_menu_id">
                                @foreach($get_menu as $all_menu)
                                    <option value="{{$all_menu->menu_id}}">{{$all_menu->deskripsi}}</option>
                                @endforeach
                            </select>
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
                    <h5 class="modal-title" id="ModalEditLabel">Edit Hak Akses</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input readonly type="text" name="edit_akses_id" id="edit_akses_id" class="form-control">
                            <label for="edit_akses_id">Akses ID</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="edit_akses" id="edit_akses" class="form-control">
                            <label for="edit_akses_id">Nama Akses</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="edit_deskripsi" id="edit_deskripsi" class="form-control">
                            <label for="edit_deskripsi">Deskripsi</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <span style="font-size:0.7rem;" for="edit_menu_id">Akses Menu</span>
                            <select style="width:100%;" class="form-control select2" multiple="multiple" id="edit_menu_id" name="edit_menu_id">
                            </select>
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
        showHakakses();
    });

    function closeModal(modal){
        $('#'+modal).modal('hide');
    }

    function showHakakses(){
        $.ajax({
            url: "{{ route('get.hakakses') }}",
            type: "POST",
            dataType: "json", 
            data:{
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
                // $('#add_akses_id').empty();
            },
            success: function (response) {
                if(response.status == 0){
					$('#t_hakakses').empty();
                    toastr.error(response.ket, "Gagal");
				}else if(response.status == 1){
                    $('#t_hakakses').empty();
                    $('#t_hakakses').html(response.t_hakakses);
                    $("#t_hakakses").DataTable( {
                        "bDestroy": true,
                        processing: true, //Feature control the processing indicator.
                        lengthChange: true,
                        pageLength: 10,
                        buttons: [  {
                                        extend: "excelHtml5",
                                        title: "List Hak Akses"
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
        $('#add_menu_id').select2({
            dropdownParent: $("#ModalAdd")
        });
    }

    function edit(i){
        var val = i;
        $.ajax({
            url: "{{ route('fill.hakakses') }}",
            type: "POST",
            dataType: "json", 
            data:{
                id:val,
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
                $('#edit_akses').empty();
                $('#edit_deskripsi').empty();
                $('#edit_menu_id').empty();
            },
            success: function (response) {
                if(response.status != 0){
                    $('#edit_akses_id').val(response.isi['akses_id']);
                    $('#edit_akses').val(response.isi['akses']);
                    $('#edit_deskripsi').val(response.isi['deskripsi']);
                    for (var x=0;x<response.get_menu.length;x++) {
                        $('#edit_menu_id').append(
                            $('<option>', {
                                value: response.get_menu[x]['menu_id'],
                                text: response.get_menu[x]['deskripsi']
                            })
                        );
                    }
                    $('#edit_menu_id').val(JSON.parse(response.isi['menu_id']));
                    $('#edit_menu_id').select2({
                        dropdownParent: $("#ModalEdit")
                    });
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
                url: "{{ route('delete.hakakses') }}",
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

                    showHakakses();
                    
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
        var edit_akses_id = $('#edit_akses_id').val();
        var edit_akses = $('#edit_akses').val();
        var edit_deskripsi = $('#edit_deskripsi').val();
        var edit_menu_id = $('#edit_menu_id').val();
        if((edit_akses_id == '') || (edit_akses_id == null)){
            toastr.error("Akses ID tidak boleh kosong", "Gagal");
        }else if((edit_akses == '') || (edit_akses == null)){
            toastr.error("Nama Akses tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('edit.hakakses') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    akses_id:edit_akses_id,
                    akses:edit_akses,
                    deskripsi:edit_deskripsi,
                    menu_id:edit_menu_id,
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

                    showHakakses();
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
        var add_akses_id = $('#add_akses_id').val();
        var add_akses = $('#add_akses').val();
        var add_deskripsi = $('#add_deskripsi').val();
        var add_menu_id = $('#add_menu_id').val();
        if((add_akses_id == '') || (add_akses_id == null)){
            toastr.error("Akses ID tidak boleh kosong", "Gagal");
        }else if((add_akses == '') || (add_akses == null)){
            toastr.error("Nama Akses tidak boleh kosong", "Gagal");
        }else{
            $.ajax({
                url: "{{ route('add.hakakses') }}",
                type: "POST",
                dataType: "json", 
                data:{
                    akses_id:add_akses_id,
                    akses:add_akses,
                    deskripsi:add_deskripsi,
                    menu_id:add_menu_id,
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

                    showHakakses();
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


