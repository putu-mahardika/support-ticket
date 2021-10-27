@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Working Logs
        </div>

        <div class="card-body">
            <div id="gridContainer"></div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script src="https://cdn3.devexpress.com/jslib/21.1.5/js/dx.all.js"></script>
    <script>
        $(function () {
            $(document).ready(() => {
                getData();
            });
            function getData() {
                $("#gridContainer").dxDataGrid({
                    dataSource: "{{ route('admin.workinglogs.data') }}",
                    keyExpr: 'id',
                    sorting: {
                        mode: "multiple"
                    },
                    columns: [
                        {
                            caption: '#',
                            cellTemplate: function(cellElement, cellInfo) {
                                cellElement.text(cellInfo.row.rowIndex + 1);
                            }
                        },
                        {dataField: "ticket.title", caption: "Ticket"},
                        {dataField: "status.name", caption: "Status"},
                        {dataField: "started_at", caption: "Start", dataType: "datetime", format: "yyyy-MM-dd HH:mm:ss"},
                        {dataField: "finished_at", caption: "End", dataType: "datetime", format: "yyyy-MM-dd HH:mm:ss"}
                    ],
                    showBorders: true,
                    filterRow: { visible: true },
                    hoverStateEnabled: true
                });
            };
        });
    </script>
@endsection
