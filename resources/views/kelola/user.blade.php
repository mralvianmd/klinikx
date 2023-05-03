
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
                <h6 class="mb-4">List User
                    <button data-toggle="modal" data-target="#ModalAdd" onclick="add()" class="btn btn-primary btn-sm px-2 py-1">
                    Tambah
                    </button>
                </h6>
                <div  class="table-responsive">
                    <table id="t_user" class="table">
                    </table>
                </div>
            </div>
        </div>


            
        <!-- Modal -->
        <div id="ModalAdd" class="modal hide fade" role="dialog" aria-labelledby="ModalAdd" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAddLabel">Tambah User</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_nama" id="add_nama" class="form-control">
                            <label for="add_nama">Nama User</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="add_email" id="add_email" class="form-control" required>
                            <label for="add_email">Email</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <select style="width:100%;" class="form-control" id="add_akses_id" name="add_akses_id">
                                @foreach($get_hakakses as $hakakses)
                                    <option value="{{$hakakses->akses_id}}">{{$hakakses->akses}}</option>
                                @endforeach
                            </select>
                            <label for="add_akses_id">Hak Akses</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="password" name="add_password" id="add_password" class="form-control" autocomplete="off">
                            <label for="add_password">Password</label>
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
                    <h5 class="modal-title" id="ModalEditLabel">Edit User</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <input hidden type="text" name="edit_user_id" id="edit_user_id" class="form-control">
                            <input type="text" name="edit_nama" id="edit_nama" class="form-control">
                            <label for="edit_nama">Nama User</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="text" name="edit_email" id="edit_email" class="form-control" required>
                            <label for="edit_email">Email</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-floating mb-3 col-6">
                            <select style="width:100%;" class="form-control" id="edit_akses_id" name="edit_akses_id">
                            </select>
                            <label for="edit_akses_id">Hak Akses</label>
                        </div>
                        <div class="form-floating mb-3 col-6">
                            <input type="password" name="edit_password" id="edit_password" class="form-control" autocomplete="off">
                            <label for="edit_password">Password (Isi jika ingin ubah)</label>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

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
        showUser();
        $("#add_email").inputmask("email");
    });

    function closeModal(modal){
        $('#'+modal).modal('hide');
    }

    function showUser(){
        $.ajax({
            url: "{{ route('get.user') }}",
            type: "POST",
            dataType: "json", 
            data:{
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
                // $('#add_akses_id').empty();
            },
            success: function (response) {
                if(response.status == 0){
					$('#t_user').empty();
                    toastr.error(response.ket, "Gagal");
				}else if(response.status == 1){
                    $('#t_user').empty();
                    $('#t_user').html(response.t_user);
                    $("#t_user").DataTable( {
                        "bDestroy": true,
                        processing: true, //Feature control the processing indicator.
                        lengthChange: true,
                        pageLength: 10,
                        buttons: [  {
                                        extend: "excelHtml5",
                                        title: "List User"
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
            url: "{{ route('fill.user') }}",
            type: "POST",
            dataType: "json", 
            data:{
                id:val,
                "_token": "{{ csrf_token() }}"},
            beforeSend: function () {
                $('#edit_nama').empty();
                $('#edit_email').empty();
                $('#edit_password').empty();
                $('#edit_akses_id').empty();
            },
            success: function (response) {
                if(response.status != 0){
                    $('#edit_user_id').val(response.id);
                    $('#edit_nama').val(response.isi['name']);
                    $('#edit_email').val(response.isi['email']);
                    $("#edit_email").inputmask("email");
                    for (var x=0;x<response.get_hakakses.length;x++) {
                        if(response.get_hakakses[x]['akses_id'] == response.isi['akses_id']){
                            $('#edit_akses_id').append(
                                $('<option>', {
                                    value: response.get_hakakses[x]['akses_id'],
                                    text: response.get_hakakses[x]['akses'],
                                    selected: "selected"
                                })
                            );
                        }else{
                            $('#edit_akses_id').append(
                                $('<option>', {
                                    value: response.get_hakakses[x]['akses_id'],
                                    text: response.get_hakakses[x]['akses']
                                })
                            );
                        }
                        
                    }
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
                url: "{{ route('delete.user') }}",
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

                    showUser();
                    
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
        var edit_user_id = $('#edit_user_id').val();
        var edit_nama = $('#edit_nama').val();
        var edit_email = $('#edit_email').val();
        var edit_password = $('#edit_password').val();
        var edit_akses_id = $('#edit_akses_id').val();

        var rules = [{
            exp: /[0-9]/,
            msg: 'Wajib terdapat minimal 1 angka'
        }, {
            exp: /[a-z]/,
            msg: 'Wajib terdapat minimal 1 huruf kecil'
        }, {
            exp: /[A-Z]/,
            msg: 'Wajib terdapat minimal 1 huruf besar'
        }, {
            exp: /^.{8,20}$/,
            msg: 'Password harus memiliki 8-20 digit'
        }];

        var pass = true;

        if((edit_user_id == '') || (edit_user_id == null)){
            toastr.error("ID user tidak boleh kosong", "Gagal");
        }else if((edit_nama == '') || (edit_nama == null)){
            toastr.error("Nama tidak boleh kosong", "Gagal");
        }else if((edit_email == '') || (edit_email == null)){
            toastr.error("Email tidak boleh kosong", "Gagal");
        }else if((edit_akses_id == '') || (edit_akses_id == null)){
            toastr.error("Hak akses tidak boleh kosong", "Gagal");
        }else{
            if(edit_password != ''){
                for (var i = 0; i < rules.length; i++) {
                    var rule = rules[i];
                    if (!rule.exp.test(edit_password)) {
                        pass = false;
                        toastr.error(rule.msg, "Gagal");
                        break;
                    }else{
                        pass = true;
                    }
                }

                if(pass == true){
                    $.ajax({
                        url: "{{ route('edit.user') }}",
                        type: "POST",
                        dataType: "json", 
                        data:{
                            user_id:edit_user_id,
                            nama:edit_nama,
                            email:edit_email,
                            akses_id:edit_akses_id,
                            password:edit_password,
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

                            showUser();
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
            }else{
                $.ajax({
                    url: "{{ route('edit.user') }}",
                    type: "POST",
                    dataType: "json", 
                    data:{
                        user_id:edit_user_id,
                        nama:edit_nama,
                        email:edit_email,
                        akses_id:edit_akses_id,
                        password:edit_password,
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

                        showUser();
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
        }
	});

    $("#btn_add").click(function(e) {
        var add_nama = $('#add_nama').val();
        var add_email = $('#add_email').val();
        var add_password = $('#add_password').val();
        var add_akses_id = $('#add_akses_id').val();
        var rules = [{
            exp: /[0-9]/,
            msg: 'Wajib terdapat minimal 1 angka'
        }, {
            exp: /[a-z]/,
            msg: 'Wajib terdapat minimal 1 huruf kecil'
        }, {
            exp: /[A-Z]/,
            msg: 'Wajib terdapat minimal 1 huruf besar'
        }, {
            exp: /^.{8,20}$/,
            msg: 'Password harus memiliki 8-20 digit'
        }];

        var pass = true;

        if((add_nama == '') || (add_nama == null)){
            toastr.error("Nama tidak boleh kosong", "Gagal");
        }else if((add_email == '') || (add_email == null)){
            toastr.error("Email tidak boleh kosong", "Gagal");
        }else if((add_akses_id == '') || (add_akses_id == null)){
            toastr.error("Hak akses tidak boleh kosong", "Gagal");
        }else if((add_password == '') || (add_password == null)){
            toastr.error("Password tidak boleh kosong", "Gagal");
        }else if(add_password.length < 8){
            toastr.error("Minimal password 8 digit tanpa spasi", "Gagal");
        }else{
            for (var i = 0; i < rules.length; i++) {
                var rule = rules[i];
                if (!rule.exp.test(add_password)) {
                    pass = false;
                    toastr.error(rule.msg, "Gagal");
                    break;
                }else{
                    pass = true;
                }
            }

            if(pass == true){
                $.ajax({
                    url: "{{ route('add.user') }}",
                    type: "POST",
                    dataType: "json", 
                    data:{
                        nama:add_nama,
                        email:add_email,
                        akses_id:add_akses_id,
                        password:add_password,
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

                        showUser();
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
        }
    });

</script>
@endsection


