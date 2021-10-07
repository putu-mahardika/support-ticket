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
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendingTickets) }}</div>
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
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($workingTickets) }}</div> 
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
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($confirmTickets) }}</div>
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
                                </div>
                                @endcan
                            </div>
                          </div>
                        </div>
                    </div>
                    <br><br>

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
                                    <!-- Feature -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-success shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-md font-weight-bold text-success text-uppercase mb-1">
                                                        Feature</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalNewFeature) }}</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-money fa-4x text-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bug -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-primary shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-md font-weight-bold text-primary text-uppercase mb-3">
                                                        Bug </div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalBug) }}</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-bug fa-4x text-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                                    <!-- Update -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-danger shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-md font-weight-bold text-danger text-uppercase mb-1">
                                                        Update </div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalUpdate) }}</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-envelope-open  fa-4x text-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                                    <!-- Report -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-warning shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-md font-weight-bold text-warning text-uppercase mb-1">
                                                        Report</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalReport, 0) }}</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-file fa-4x text-300"></i>
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
                                    <!-- Total High -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-danger shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-md font-weight-bold text-danger text-uppercase mb-3">
                                                        High </div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalHigh) }}</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-battery-full fa-4x text-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                                    <!-- Total Medium -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-warning shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-md font-weight-bold text-warning text-uppercase mb-1">
                                                        Medium </div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalMedium) }}</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-battery-half  fa-4x text-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                                    <!-- Total Low -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-success shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-md font-weight-bold text-success text-uppercase mb-1">
                                                        Low </div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalLow) }}</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-battery-quarter fa-4x text-300"></i>
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
                    
                    {{-- Bar Chart DevExtrem --}}
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
                    <br><br>

                    {{-- Table Last Comment  --}}
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
                { valueField: "value", name: "Tiket", color: "#4e44db" }
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