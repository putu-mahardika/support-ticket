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
                        {{-- Donout Chart --}}
                        <div class="col-lg-12">
                            <div class="card">
                               <div class="text-center card-header">
                                    Current Condition
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <div id="kategori"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="prioritas"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="status"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="text-center card-header">
                                    Jumlah Tiket Harian (Bulan : {{ $date->locale('id')->monthName }})
                                </div>
                                <div class="card-body" style="overflow-x: scroll;">
                                    <div class="demo-container">
                                        <div id="chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bar Chart --}}
                        {{-- <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    Jumlah Tiket Harian (Bulan : {{ $date->locale('id')->monthName }})
                                </div>

                            </div>
                        </div><br><br> --}}
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
                @endcan
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


    var data = [
                {country: "kategori", commodity: "Bug", total: 9 },
                {country: "kategori", commodity: "Update", total: 2 },
                {country: "kategori", commodity: "Report", total: 3 },
                {country: "kategori", commodity: "Bug", total: 1 },

                { country: "prioritas", commodity: "Low", total: 5 },
                { country: "prioritas", commodity: "High", total: 2 },
                { country: "prioritas", commodity: "mediium", total:3  },


                { country: "status", commodity: "Open", total:3  },
                { country: "status", commodity: "Working", total: 5 },
                { country: "status", commodity: "Pending", total:  0},
                { country: "status", commodity: "Confirm", total: 7 },
                { country: "status", commodity: "Closed", total: 7 },
              ];

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
                argumentField: "commodity",
                valueField: "total",
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
                    country = pieChart.getAllSeries()[0].getVisiblePoints()[0].data.country,
                    content = $('<svg><circle cx="100" cy="100" fill="#eee" r="' + (pieChart.getInnerRadius() - 6) + '"></circle>' +
                        '<image x="70" y="58" width="60" height="40" href="' + "{{ asset('images/help.png')}}" + '"/>' +
                        '<text text-anchor="middle" style="font-size: 18px" x="100" y="120" fill="#494949">' +
                        '<tspan x="100" >' + country + '</tspan>' +
                        '<tspan x="100" dy="20px" style="font-weight: 600">' +
                        formatNumber(total) +
                        '</tspan></text></svg>');

                container.appendChild(content.get(0));
            }
        };

        $("#kategori")
            .dxPieChart($.extend({}, commonSettings, {
                dataSource: {
                    store: data,
                    filter: ["country", "=", "Kategori"]
                }
            }));

        $("#prioritas")
            .dxPieChart($.extend({}, commonSettings, {
                dataSource: {
                    store: data,
                    filter: ["country", "=", "Prioritas"]
                }
            }));

        $("#status")
            .dxPieChart($.extend({}, commonSettings, {
                dataSource: {
                    store: data,
                    filter: ["country", "=", "Status"]
                }
            }));
    });

</script>
@endsection
