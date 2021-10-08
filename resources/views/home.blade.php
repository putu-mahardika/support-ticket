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
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Kategory --}}
                    <div  class="col-lg-12" id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                <button class="btn btn-success" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    <i class="fas fa-chevron-down"></i><p class="text-sm font-weight-bold text-white text-uppercase mb-3" style="display: inline">  Kategori</p>
                                </button>
                                </h5>
                            </div>

                            <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    @can('ticket_show')
                                        <div class="row">
                                            @foreach ($categories as $category)
                                                <div class="col-xl-3 col-md-6 mb-4">
                                                    <div class="card shadow h-100 py-2" style="border-left: 0.25rem solid {{ $category->color }} !important;">
                                                        <div class="card-body py-0">
                                                            <div class="row no-gutters align-items-center">
                                                                <div class="col mr-2">
                                                                    <div class="text-md font-weight-bold text-uppercase mb-1" style="color: {{ $category->color }}">
                                                                        {{ $category->name }}
                                                                    </div>
                                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                        {{
                                                                            number_format(
                                                                                $tickets->where('category.id', $category->id)->count()
                                                                            )
                                                                        }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <i class="{{ $category->icon }}  fa-2x "></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>

                    {{-- Cart & Last Comment  --}}
                    <div  class="col-lg-12" id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                <button class="btn btn-warning" data-toggle="collapse" data-target="#collapseCart" aria-expanded="true" aria-controls="collapseTwo">
                                    <i class="fas fa-chevron-down"></i><p class="text-sm font-weight-bold text-white text-uppercase mb-3" style="display: inline">  Cart & Comment</p>
                                </button>
                                </h5>
                            </div>

                            <div id="collapseCart" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    @can('ticket_show')
                                        {{-- Cart & Last Comment  --}}
                                        <div class="row">
                                            {{-- Chart --}}
                                            <div class="col-lg-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        Jumlah Tiket Harian (Bulan : {{ $date->locale('id')->monthName }})
                                                    </div>
                                                    <div class="card-body" style="overflow-x: scroll;">
                                                        <div class="demo-container">
                                                            <div id="chart"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br><br>
                        
                                            {{-- Table Last Comment  --}}
                                            <div class="col-lg-6">
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
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>

                    {{-- Status --}}
                    <div  class="col-lg-12" id="accordion">
                        <div class="card">

                          <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                              <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fas fa-chevron-down"></i><p class="text-sm font-weight-bold text-white text-uppercase mb-3" style="display: inline">  Status</p>
                              </button>
                            </h5>
                          </div>

                          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                @can('ticket_show')
                                    <div class="row">
                                        <!-- Total Ticket -->
                                        <div class="col-xl-3 col-md-6 mb-4">
                                            <div class="card border-left-primary shadow h-100 py-2">
                                                <div class="card-body py-0">
                                                    <div class="row no-gutters align-items-center">
                                                        <div class="col mr-2">
                                                            <div class="text-md font-weight-bold text-primary text-uppercase mb-3">
                                                            Total Ticket </div>
                                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($tickets->count()) }}</div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <i class="fas fa-calculator fa-2x "></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @foreach ($statuses as $status)
                                            <div class="col-xl-3 col-md-6 mb-4">
                                                <div class="card shadow h-100 py-2" style="border-left: 0.25rem solid {{ $status->color }} !important;">
                                                    <div class="card-body py-0">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-md font-weight-bold text-uppercase mb-1" style="color: {{ $status->color }}">
                                                                    {{ $status->name }}
                                                                </div>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                    {{
                                                                        number_format(
                                                                            $tickets->where('status.id', $status->id)->count()
                                                                        )
                                                                    }}
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-envelope-open  fa-2x text-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="col-xl-6 col-md-12 mb-4">
                                            <div class="card border-left-danger shadow h-100 py-2">
                                                <div class="card-body py-0">
                                                    <div class="row no-gutters align-items-center">
                                                        <div class="col mr-2">
                                                            <div class="text-md font-weight-bold text-danger text-uppercase mb-1">
                                                            Rata-rata penyelesaian masalah</div>
                                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $avgTime }}</div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <i class="fas fa-envelope fa-2x"></i>
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
                    </div>
                    <br><br>
                    {{-- Prioritas --}}
                    <div  class="col-lg-12" id="accordion">
                        <div class="card">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                            <button class="btn btn-danger" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                <i class="fas fa-chevron-down"></i><p class="text-sm font-weight-bold text-white text-uppercase mb-3" style="display: inline">  Prioritas</p>
                            </button>
                            </h5>
                        </div>

                        <div id="collapseThree" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">

                                @can('ticket_show')
                                    <div class="row">
                                        @foreach ($priorities as $priority)
                                            <div class="col-xl-3 col-md-6 mb-4">
                                                <div class="card shadow h-100 py-2" style="border-left: 0.25rem solid {{ $priority->color }} !important;">
                                                    <div class="card-body py-0">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-md font-weight-bold text-uppercase mb-1" style="color: {{ $priority->color }}">
                                                                    {{ $priority->name }}
                                                                </div>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                    {{
                                                                        number_format(
                                                                            $tickets->where('priority.id', $priority->id)->count()
                                                                        )
                                                                    }}
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="{{ $priority->icon }}  fa-2x"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endcan
                            </div>
                        </div>
                        </div>
                    </div>
                    <br><br>
                    




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
                { valueField: "value", name: "Tiket", color: "#f6c23e" }
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
        columns: ["tgl", "proyek", "judul_tiket", "deskripsi"],
        showBorders: true,
        filterRow: { visible: true },
        headerFilter: { visible: true },
    });
});

</script>
@endsection
