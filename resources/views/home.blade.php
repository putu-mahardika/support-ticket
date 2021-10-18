@extends('layouts.admin')
@section('css')
<style>
    #chart {
        height: 440px;
        padding-right: 10px;
    }
    .pies-container {
    margin: auto;
    width: 800px;
    }

    .pies-container > div {
        width: 400px;
        float: left;
        margin-top: -50px;
    }

    .long-title {
        font-weight: 200;
        font-size: 28px;
        text-align: center;
        margin-bottom: 20px;
    }

    #gauge-demo {
    height: 440px;
    width: 100%;
    }

    #gauge {
        width: 80%;
        height: 100%;
        float: left;
    }

    #seasons {
        width: 20%;
        float: left;
        text-align: left;
        margin-top: 20px;
    }

</style>
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="content">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                @can('ticket_show')

                    <div class="col-lg-12">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div class="card bg-primary text-white shadow">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-lg font-weight-bold text-white text-uppercase mb-1">Total Tickets</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-1000">
                                                        <h2 class="counter">{{ number_format($tickets->count()) }}</h2>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-ticket-alt fa-2x text-gray-1000 rotate-15"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div class="card bg-warning text-white shadow">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-lg font-weight-bold text-white text-uppercase mb-1">Average Ticket Finish Time</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-1000">
                                                        <h2 class="counter">{{ ($avgTime) }}</h2>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-clock fa-2x text-gray-1000 rotate-15"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                        {{-- Doughnut Chart --}}
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Current Condition</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 d-flex justify-content-center position-relative ">
                                            <label class="position-absolute">Category</label>
                                            <div id="kategori" class="chart-pie"></div>
                                        </div>
                                        <div class="col-md-4 d-flex justify-content-center position-relative ">
                                             <label class="position-absolute" style="left: 8.75rem;">Priority</label>
                                            <div id="prioritas" class="chart-pie"></div>
                                        </div>
                                        <div class="col-md-4 d-flex justify-content-center position-relative ">
                                             <label class="position-absolute" style="left: 8.75rem;">Status</label>
                                            <div id="status" class="chart-pie"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Bar Chart --}}
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"> Jumlah Tiket Harian (Bulan : {{ $date->locale('id')->monthName }}) </h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="card-body" style="overflow-x: scroll;">
                                            <div class="demo-container">
                                                <div id="chart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- WEEKLY CHART -->
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="text-center card-header">
                                            Jumlah Tiket Harian (Minggu : {{ $weekNow }})
                                        </div>
                                        <div class="card-body" style="overflow-x: scroll;">
                                            <div class="demo-container">
                                                <div id="chart1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @can('user_comment')
                        {{-- Table Last Comment  --}}
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"> Last Comments </h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="demo-container">
                                            <div id="gridContainer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                @endcan
    </div>
@endsection
@section('scripts')
@parent
<script src="https://cdn3.devexpress.com/jslib/21.1.5/js/dx.all.js"></script>
<script src="https://unpkg.com/counterup2@2.0.2/dist/index.js">	</script>
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
        $("#chart1").dxChart({
            dataSource: "{{ url('admin/getTicketsThisWeek') }}",

            commonSeriesSettings: {
                argumentField: "name",
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
                width: 1000
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

    $(function () {
        var formatNumber = new Intl.NumberFormat("en-US", { maximumFractionDigits: 0 }).format;
        var commonSettings = {
            innerRadius: 0.65,
            resolveLabelOverlapping: "shift",
            sizeGroup: "piesGroup",
            legend: {
                visible: false
            },
            type: "doughnut",
            series: [{
                argumentField: "name",
                valueField: "value",
                label: {
                    visible: true,
                    connector: {
                        visible: true
                    },
                    format: "fixedPoint",
                    backgroundColor: "none",
                    customizeText: function(e) {
                        return e.argumentText + "\n" + e.valueText;
                    }
                }
            }],
            centerTemplate: function(pieChart, container) {
                var total = pieChart.getAllSeries()[0].getVisiblePoints().reduce(function(s, p) { return s + p.originalValue; }, 0),
                    // country = pieChart.getAllSeries()[0].getVisiblePoints()[0].data.country,
                    content = $('<svg><circle cx="100" cy="100" fill="#fff" r="' + (pieChart.getInnerRadius() - 6) + '"></circle>' +
                        // '<image x="70" y="58" width="60" height="40" href="' + "{{ asset('images/menu.png')}}" + '"/>' +
                        '<text text-anchor="middle" style="font-size: 50px" x="100" y="120" fill="#494949"></text></svg>' );
                        // '<tspan x="100" >' + country + '</tspan>' +
                        // '<tspan x="100" dy="-9px" style="font-weight: 600; size: 100px;">' +
                        // formatNumber(total) +
                        // '</tspan></text></svg>');

                container.appendChild(content.get(0));
            }
        };

        $("#kategori")
            .dxPieChart($.extend({}, commonSettings, {
                dataSource: `{{ url('admin/getDataDoughnut') }}?table=categories`
            }));

        $("#prioritas")
            .dxPieChart($.extend({}, commonSettings, {
                dataSource: `{{ url('admin/getDataDoughnut') }}?table=priorities`
            }));

        $("#status")
            .dxPieChart($.extend({}, commonSettings, {
                dataSource: `{{ url('admin/getDataDoughnut') }}?table=statuses`,
            }));
    });

    const counterUp = window.counterUp.default

    const el = document.querySelector( '.counter' )

    // Start counting, typically you need to call this when the
    // element becomes visible, or whenever you like.
    counterUp( el, {
        duration: 2000,
        delay: 16,
    } )

</script>
<script>

</script>
@endsection
