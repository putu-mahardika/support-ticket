@extends('layouts.admin')
@section('css')
<style>
    #chart {
        height: 440px;
        padding-right: 10px;
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @can('ticket_show')
                    <div class="row">
                        <!-- Total Ticket -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-md font-weight-bold text-primary text-uppercase mb-3">
                                            Total Ticket </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalTickets) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calculator fa-4x text-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Open Ticket -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-md font-weight-bold text-primary text-uppercase mb-1">
                                            Open </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($openTickets) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-envelope-open  fa-4x text-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Pending -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-md font-weight-bold text-warning text-uppercase mb-1">
                                            Pending</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-pause-circle fa-4x text-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Working -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-md font-weight-bold text-success text-uppercase mb-1">
                                            Working</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chalkboard-teacher fa-4x text-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
    
                        <!-- Confirm Client -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-md font-weight-bold text-info text-uppercase mb-1">
                                            Confirm Client</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-4x text-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-md font-weight-bold text-danger text-uppercase mb-1">
                                            Close</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($closedTickets) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-envelope fa-4x tex-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-md font-weight-bold text-danger text-uppercase mb-1">
                                            Rata-rata penyelesaian masalah</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $avgTime }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-envelope fa-4x tex-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan

                </div>
                
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Jumlah Tiket Harian (Bulan : {{ $monthName }})
                </div>
                <div class="card-body" style="overflow-x: scroll;">
                    <div class="demo-container">
                        <div id="chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Last Comments
                </div>
                <div class="card-body" style="overflow-x: scroll;">
                    <div class="demo-container">
                        <div id="gridContainer"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('scripts')
@parent
<script src="https://cdn3.devexpress.com/jslib/21.1.5/js/dx.all.js"></script>
<script>
    $(function(){
        $("#chart").dxChart({
            dataSource: "{{ url('admin/getJumlahTiketHarian') }}",

            commonSeriesSettings: {
                argumentField: "tgl",
                valueField: "value",
                type: "bar",
                hoverMode: "allArgumentPoints",
                selectionMode: "allArgumentPoints",
                label: {
                    visible: true,
                    format: {
                        type: "fixedPoint",
                        precision: 0
                    }
                }
            },
            series: [
                { valueField: "value", name: "Tiket", color: "#80b3ff" }
            ],
            legend: {
                visible: false
            },
            size: {
                height: 400,
                width: 1200
            },
            argumentAxis: {
                allowDecimals: false,
                title: 'Tanggal',
                label: {
                    wordWrap: "none",
                    overlappingBehavior: "stagger",
                }
            },
            valueAxis: {
                title: 'Jumlah',
                allowDecimals: false,
            },
        });
    });

    $(function(){
    $("#gridContainer").dxDataGrid({
        dataSource: "{{ url('admin/getLastComment') }}",
        // keyExpr: 'ID',
        columns: ["tgl", "proyek", "author", "judul_tiket", "deskripsi"],
        showBorders: true,
        filterRow: { visible: true },
        headerFilter: { visible: true },
    });
});
</script>
@endsection