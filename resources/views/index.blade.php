
@extends('layout.main')

@section('content')
<!-- Content Start -->
<div class="content">
    @include('layout.navbar')

    <!-- Sale & Revenue Start -->
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
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Pasien Terdaftar</p>
                        <h6 class="mb-0">{{$pasien_terdaftar}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Pasien Antrian Hari Ini</p>
                        <h6 class="mb-0">{{$pasien_antrian_today}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Pasien Selesai Periksa</p>
                        <h6 class="mb-0">{{$pasien_antrian_selesai}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Pasien Terdaftar dan Periksa</p>
                        <h6 class="mb-0">{{$count_pasien_antrian_aktif}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale & Revenue End -->


    <!-- Sales Chart Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Rekapitulasi Obat</h6>
                    </div>
                    <canvas id="rekap-obat"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Sales Chart End -->

    @include('layout.footer')
</div>
<!-- Content End -->
@endsection

@section('scripts')
<script>

    // rekap-obat
    var ctx1 = $("#rekap-obat").get(0).getContext("2d");
    var label_obat = new Array();
    var label_obat_id = new Array();
    var label_qty_in = new Array();
    var label_qty_out = new Array();
    var label_qty_stock = new Array();
    var get_chart = "{{$convert_chart_obat}}";
    var get_chart_label = "{{$convert_label_chart_obat}}";
    var parse = (JSON.parse(get_chart.replace(/&quot;/g,'"')));
    var parse_label = (JSON.parse(get_chart_label.replace(/&quot;/g,'"')));
    var myChart1 = new Chart(ctx1, {
        type: "bar",
        data: {
            labels: parse.obat,
            datasets: parse_label,
            // datasets: [{
            //         label: "In",
            //         data: [15, 30, 55, 65, 60, 80, 95],
            //         backgroundColor: "rgba(0, 156, 255, .7)"
            //     },
            //     {
            //         label: "Out",
            //         data: [8, 35, 40, 60, 70, 55, 75],
            //         backgroundColor: "rgba(0, 156, 255, .5)"
            //     },
            //     {
            //         label: "Stock",
            //         data: [12, 25, 45, 55, 65, 70, 60],
            //         backgroundColor: "rgba(0, 156, 255, .3)"
            //     }
            // ]
            },
        options: {
            responsive: true
        }
    });

</script>
@endsection


