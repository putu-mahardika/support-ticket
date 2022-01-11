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

    <div class="ml-2 mb-4">
        <h3 class="mb-0 text-gray-800">Dashboard</h3>
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
                                            <h2 id="totalTicketCounter" class="counter">0</h2>
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
                                        <div class="text-lg font-weight-bold text-white text-uppercase mb-1">Average Ticket
                                            Finish Time</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-1000">
                                            <h2 id="avgTime" class="counter"></h2>
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
                        <h6 class="m-0 font-weight-bold text-primary" id="headerCurrentCondition">Current Condition</h6>
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

            <!-- DAILY CHART -->
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary" id="headerDailyTicket">Jumlah Tiket Harian</h6>
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
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-2">
                        <h6 class=" m-0 font-weight-bold text-primary" id="headerWeeklyTicket">Jumlah Tiket Harian </h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="weekLabel">Week</span>
                                    </div>
                                    {{-- <input id="weekInput" type="number" class="form-control" value="{{ now()->week }}" min="1" max="{{ now()->weeksInYear() }}" aria-describedby="weekLabel"> --}}
                                    <select name="weekInput" id="weekInput" class="form-control"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card-body">
                                    <div class="demo-container">
                                        <div id="chart1"></div>
                                    </div>
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
<script>
    let dailyTicketWeek = null;
    let dailyTicketMonth = null;
    let currCategory = null;
    let currPriority = null;
    let currStatus = null;
    let formatNumber = new Intl.NumberFormat("en-US", { maximumFractionDigits: 0 }).format;
    let commonSettings = {
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
                content = $('<svg><circle cx="100" cy="100" fill="#fff" r="' + (pieChart.getInnerRadius() - 6) + '"></circle>' +
                    '<text text-anchor="middle" style="font-size: 50px" x="100" y="120" fill="#494949"></text></svg>' );

            container.appendChild(content.get(0));
        },
        loadingIndicator: {
            enabled: true,
        },
    };

    function counterUp(el, count, delay = 100) {
        let start = 0;
        let interval = setInterval(() => {
            start += Math.max(Math.floor(count/delay), 1);
            $(el).text(start);
            if (start >= count) {
                clearInterval(interval);
                $(el).text(count);
            }
        }, delay/((50/100)*count));
    }

    function loadCurrentConditionChart() {
        currCategory = $("#kategori").dxPieChart($.extend({}, commonSettings, {
            dataSource: `{{ url('admin/getDataDoughnut') }}?table=categories&${generateFilter()}`
        })).dxPieChart('instance');

        currPriority = $("#prioritas").dxPieChart($.extend({}, commonSettings, {
            dataSource: `{{ url('admin/getDataDoughnut') }}?table=priorities&${generateFilter()}`
        })).dxPieChart('instance');

        currStatus = $("#status").dxPieChart($.extend({}, commonSettings, {
            dataSource: `{{ url('admin/getDataDoughnut') }}?table=statuses&${generateFilter()}`,
        })).dxPieChart('instance');
    }

    function loadDailyTicketWeek() {
        dailyTicketWeek = $("#chart1").dxChart({
            dataSource: `{{ url('admin/getTicketsThisWeek') }}?${generateFilter()}`,
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
                { valueField: "value", name: "Tiket", color: "#009423" }
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
                title: 'Hari',
                label: {
                    wordWrap: "none",
                    overlappingBehavior: "stagger",
                }
            },
            valueAxis: {
                title: 'Jumlah',
                allowDecimals: false,
            },
            loadingIndicator: {
                enabled: true,
            },
        }).dxChart('instance');
    }

    function loadDailyTicketMonth() {
        dailyTicketMonth = $("#chart").dxChart({
            dataSource: `{{ url('admin/getJumlahTiketHarian') }}?${generateFilter()}`,
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
                { valueField: "value", name: "Tiket", color: "#06d638" }
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
            loadingIndicator: {
                enabled: true,
            },
        }).dxChart('instance');
    }

    function loadStatPanel(params) {
        $.get(`{{ route('admin.statPanel') }}?${generateFilter()}`, res => {
            counterUp(
                $('#totalTicketCounter'),
                res.ticketsCount,
                1000
            );
            $('#avgTime').text(res.avgTime);
        });
    }

    function generateFilter() {
        let monthFilter = $('#monthFilter').val();
        let weekFilter = $('#weekInput').val();
        return `monthFilter=${monthFilter}&weekFilter=${weekFilter}`;
    }

    function loadEvents() {
        $('#formModalFilterMonth').on('submit', function (e) {
            e.preventDefault();
            $('#monthFilter').val($('#monthFilterModal').val());
            $('#formMonthFilter').trigger('submit');
            $('#modalFilterMonth').modal('hide');
        });

        $('#formMonthFilter').on('submit', function (e) {
            e.preventDefault();
            loadWeekInput();
            $('#monthFilterModal').val($('#monthFilter').val());
            let requestParams = generateFilter();

            changeLabelHeader();
            loadStatPanel();
            currCategory.option('dataSource', `{{ url('admin/getDataDoughnut') }}?table=categories&${requestParams}`);
            currCategory.refresh();

            currPriority.option('dataSource', `{{ url('admin/getDataDoughnut') }}?table=priorities&${requestParams}`);
            currPriority.refresh();

            currStatus.option('dataSource', `{{ url('admin/getDataDoughnut') }}?table=statuses&${requestParams}`);
            currStatus.refresh();

            dailyTicketMonth.option('dataSource', `{{ url('admin/getJumlahTiketHarian') }}?${requestParams}`);
            dailyTicketMonth.refresh();

            dailyTicketWeek.option('dataSource', `{{ url('admin/getTicketsThisWeek') }}?${requestParams}`);
            dailyTicketWeek.refresh();

        });

        $('#weekInput').on('change', function () {
            if ($(this).val() > $(this).attr('max')) {
                $(this).val($(this).attr('max'));
            }
            let requestParams = generateFilter();
            dailyTicketWeek.option('dataSource', `{{ url('admin/getTicketsThisWeek') }}?${requestParams}`);
            dailyTicketWeek.refresh();
        });
    }

    function loadWeekInput() {
        let monthFilter = $('#monthFilter').val();
        $.get(`{{ route('admin.weeksInMonth') }}?${generateFilter()}&onlyWeek=true`, res => {
            let html = "";
            res.forEach(week => {
                html += `<option value="${week}">${week}</option>`;
            });
            $('#weekInput').html(html);
        });
    }

    function changeLabelHeader() {
        $('#headerCurrentCondition').text(`Group ( ${moment($('#monthFilter').val()).format('MMMM YYYY')} )`);
        $('#headerDailyTicket').text(`Grafik Tiket Harian ( ${moment($('#monthFilter').val()).format('MMMM YYYY')} )`);
        $('#headerWeeklyTicket').text(`Grafik Tiket Mingguan ( ${moment($('#monthFilter').val()).format('MMMM YYYY')} )`);
        // $('#headerCurrentCondition').append(` ( ${moment($('#monthFilter').val()).format('MMMM YYYY')} )`);
    }

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

    $(document).ready(() => {
        changeLabelHeader();
        loadStatPanel();
        loadEvents();
        loadCurrentConditionChart();
        loadDailyTicketMonth();
        loadDailyTicketWeek();
        loadWeekInput();
    });

</script>
@endsection
