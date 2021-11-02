@extends('layouts.admin')

@section('styles')
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <span>
                    Working Logs
                </span>
                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#modalRecreateLog">
                    <i class="fas fa-sync-alt mr-2"></i> Recreate Logs
                </button>
            </div>
        </div>

        <div class="card-body">
            <div id="gridContainer"></div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalRecreateLog" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalRecreateLogLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRecreateLogLabel">Choose one or more</h5>
                </div>
                <div class="modal-body">
                    <div id="gridContainerTicket"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnRecreateClose" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btnRecreate" class="btn btn-primary">
                        <i class="fas fa-sync-alt mr-2"></i> Recreate Logs
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script src="https://cdn3.devexpress.com/jslib/21.1.5/js/dx.all.js"></script>
    <script src="{{ asset('js/devExtreme.locale.id.js') }}"></script>
    <script>
        DevExpress.localization.loadMessages(idLocale);
        DevExpress.localization.locale("id");
        let selectedTickets = [];
        let dataGrid = null;
        let dataGridTickets = null;
        $(function () {
            $(document).ready(() => {
                getData();

                $('#modalRecreateLog').on('show.bs.modal', function (e) {
                    selectedTickets = [];
                    $('#btnRecreate').prop('disabled', selectedTickets.length <= 0);

                    if (dataGridTickets) {
                        dataGridTickets.deselectAll();
                        dataGridTickets.clearSelection();
                    }
                });

                $('#modalRecreateLog').on('shown.bs.modal', function (e) {
                    getDataTickets();
                });

                $('#modalRecreateLog').on('hidden.bs.modal', function (e) {
                    dataGrid.refresh();
                });

                $('body').on('click', '#btnRecreate', function () {
                    $('#gridContainerTicket').addClass('disabledContainer');
                    $('#btnRecreateClose').prop('disabled', true);
                    $(this).prop('disabled', true);
                    $(this).html(`
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    `);
                    recreateLogs();
                });
            });

            function selectionChanged(e) {
                selectedTickets = e.selectedRowsData.map((ticket) => {
                    return ticket.id;
                });

                $('#btnRecreate').prop('disabled', selectedTickets.length <= 0);
            }

            function getData() {
                dataGrid = $("#gridContainer").dxDataGrid({
                    dataSource: "{{ route('admin.workinglogs.data') }}",
                    keyExpr: 'id',
                    sorting: {
                        mode: "multiple"
                    },
                    columnAutoWidth: true,
                    columns: [
                        {
                            caption: '#',
                            cellTemplate: function(cellElement, cellInfo) {
                                cellElement.text(cellInfo.row.rowIndex + 1);
                            },
                            dataType: 'number'
                        },
                        {dataField: "ticket.title", caption: "Ticket"},
                        {dataField: "status.name", caption: "Status"},
                        {dataField: "started_at", caption: "Start", dataType: "datetime", format: "EEEE, yyyy-MM-dd HH:mm:ss"},
                        {dataField: "finished_at", caption: "End", dataType: "datetime", format: "EEEE, yyyy-MM-dd HH:mm:ss"}
                    ],
                    showBorders: true,
                    filterRow: { visible: true },
                    hoverStateEnabled: true,
                }).dxDataGrid("instance");
            };

            function getDataTickets() {
                dataGridTickets = $("#gridContainerTicket").dxDataGrid({
                    dataSource: "{{ route('admin.workinglogs.tickets') }}",
                    keyExpr: 'id',
                    sorting: {
                        mode: "multiple"
                    },
                    selection: {
                        mode: "multiple",
                        showCheckBoxesMode: "always"
                    },
                    columns: [
                        {dataField: "title", caption: "Ticket"},
                        {dataField: "work_start", caption: "Start", dataType: "datetime", format: "EEEE, yyyy-MM-dd HH:mm:ss"},
                        {dataField: "work_end", caption: "End", dataType: "datetime", format: "EEEE, yyyy-MM-dd HH:mm:ss"}
                    ],
                    showBorders: true,
                    filterRow: { visible: true },
                    hoverStateEnabled: true,
                    onSelectionChanged: selectionChanged
                }).dxDataGrid("instance");
            };
        });

        function recreateLogs() {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.workinglogs.recreateLogs') }}",
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify({
                    selectedTickets: selectedTickets,
                }),
                success: function(response) {
                    $('#modalRecreateLog').modal('hide');
                    $('#gridContainerTicket').removeClass('disabledContainer');
                    $('#btnRecreateClose').prop('disabled', false);
                    $('#btnRecreate').prop('disabled', false);
                    $('#btnRecreate').html(`
                        <i class="fas fa-sync-alt mr-2"></i> Recreate Logs
                    `);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    </script>
@endsection
