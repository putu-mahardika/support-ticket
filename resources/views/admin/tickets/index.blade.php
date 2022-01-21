@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 10px;" class="row justify-content-between">
        @can('ticket_create')
            <div class="col-lg-2 col-md-3 mb-3">
                <a class="btn btn-block btn-success" href="{{ route("admin.tickets.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.ticket.title_singular') }}
                </a>
            </div>
        @endcan

        <div class="col-md-6 mb-3 text-right">
            <div class="row justify-content-end">
                <div class="col-lg-6 col-md-7">
                    <button id="btnRecalculateDuration" class="btn btn-block btn-outline-primary mb-3">
                        <span id="labelSelectedTickets" class="badge badge-danger"></span>
                        Hitung ulang durasi
                    </button>
                </div>
                <div class="col-lg-6 col-md-5">
                    <a class="btn btn-block btn-primary mb-3" href="{{ route("admin.tickets.showReport") }}">
                        Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.ticket.title_singular') }} {{ trans('global.list') }}
        </div>

        @if(session('status'))
            <div class="alert alert-success rounded-0" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="card-body">
            <div id="gridContainer"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let dataGrid = null;
        let selectedTickets = [];
        let dataSource = new DevExpress.data.CustomStore.constructor({
            key: "id",
            load: function(loadOptions) {
                var d = $.Deferred();
                var params = {};
                [
                    "filter",
                    "group",
                    "groupSummary",
                    "parentIds",
                    "requireGroupCount",
                    "requireTotalCount",
                    "searchExpr",
                    "searchOperation",
                    "searchValue",
                    "select",
                    "sort",
                    "skip",
                    "take",
                    "totalSummary",
                    "userData"
                ].forEach(function(i) {
                    if(i in loadOptions && isNotEmpty(loadOptions[i])) {
                        params[i] = JSON.stringify(loadOptions[i]);
                    }
                });

                $.getJSON("{{ route('admin.tickets.data') }}", params)
                    .done(function(response) {
                        d.resolve(response.data, {
                            totalCount: response.totalCount,
                        });
                    })
                    .fail(function() {
                        throw "Data loading error"
                    });
                return d.promise();
            },
        });

        function isNotEmpty(value) {
            return value !== undefined && value !== null && value !== "";
        }

        function getData() {
            dataGrid = $("#gridContainer").dxDataGrid({
                dataSource: dataSource,
                keyExpr: 'id',
                sorting: {
                    mode: "multiple"
                },
                selection: {
                    mode: 'multiple',
                    selectAllMode: 'page',
                    showCheckBoxesMode: 'always'
                },
                columnAutoWidth: true,
                columns: [
                    {
                        caption: '#',
                        cellTemplate: function(cellElement, cellInfo) {
                            cellElement.text(cellInfo.row.rowIndex + 1);
                        },
                        dataType: 'number',
                    },
                    {
                        dataField: 'created_at',
                        caption: 'Tgl Pembuatan',
                        dataType: 'datetime',
                        format: 'yyyy-MM-dd HH:mm:ss'
                    },
                    {
                        dataField: 'project.name',
                        caption: 'Project'
                    },
                    {
                        dataField: "code"
                    },
                    {
                        dataField: "title",
                        cellTemplate: function (cellElement, cellInfo) {
                            let commentCount = cellInfo.data.comments_count > 0 ? ` (${cellInfo.data.comments_count})` : '';
                            let attachmentCount = cellInfo.data.attachments.length > 0 ? ` <i class="fas fa-paperclip"></i>` : '';
                            cellElement.html(
                                `<a href="{{ route('admin.tickets.index') }}/${cellInfo.data.id}">${cellInfo.value}${commentCount}${attachmentCount}</a>`
                            );
                        }
                    },
                    {
                        dataField: 'comments',
                        cellTemplate: function (cellElement, cellInfo) {
                            if (cellInfo.value.length > 0) {
                                let attachmentCount = $(cellInfo.value).last()[0]['attachments'].length > 0 ? ` <i class="fas fa-paperclip"></i>` : '';
                                cellElement.html(
                                    strLimit($(cellInfo.value).last()[0]['comment_text'], 15) + attachmentCount
                                );
                            } else {
                                cellElement.html('-');
                            }
                        },
                        caption: 'Last Comment',
                        allowFiltering: false,
                        allowSorting: false,
                    },
                    {
                        dataField: 'status.name',
                        caption: 'Status'
                    },
                    {
                        dataField: 'priority.name',
                        caption: 'Priority'
                    },
                    {
                        dataField: 'category.name',
                        caption: 'Kategori'
                    },
                    {
                        dataField: 'author_name',
                        caption: 'Nama Pelapor'
                    },
                    {
                        dataField: 'author_email',
                        caption: 'Email Pelapor'
                    },
                    {
                        dataField: 'assigned_to_user.name',
                        caption: 'PIC',
                    },
                    {
                        dataField: 'work_duration'
                    },
                    {
                        caption: 'Actions',
                        dataField: 'id',
                        cellTemplate: (cellElement, cellInfo) => {
                            cellElement.html(
                                `<a class="btn btn-warning btn-sm" href="{{ route('admin.tickets.index') }}/${cellInfo.value}/edit">
                                    <i class="fas fa-edit fa-sm"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" type="button" onclick="actionDelete(${cellInfo.value});">
                                    <i class="fas fa-trash-alt fa-sm"></i>
                                </button>`
                            );
                        },
                        allowFiltering: false,
                        allowSorting: false,
                        minWidth: 100,
                    }
                ],
                showBorders: true,
                filterRow: { visible: true },
                hoverStateEnabled: true,
                onSelectionChanged: function (e) {
                    selectedTickets = e.selectedRowKeys;
                    $('#labelSelectedTickets').html(selectedTickets.length > 0 ? selectedTickets.length : '');
                },
                remoteOperations: {
                    paging: true,
                    filtering: true,
                    sorting: true,
                },
                wordWrapEnabled: true,
            }).dxDataGrid("instance");
        }

        function actionDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                showDenyButton: true,
                showConfirmButton: false,
                showCancelButton: true,
                denyButtonText: `Delete`,
            }).then((result) => {
                if (result.isDenied) {
                    $.ajax({
                        url: `{{ route('admin.tickets.index') }}/${id}`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE'
                        },
                        success: (res) => {
                            location.reload();
                        },
                        error: (error) => {
                            Swal.fire('Delete record is fail', '', 'error');
                        }
                    });
                }
            });
        }

        function loadEvents() {
            $('#btnRecalculateDuration').on('click', function () {
                if (selectedTickets.length <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed!',
                        text: 'Pilih 1 atau lebih tiket.'
                    });
                    return false;
                }

                $.ajax({
                    url: "{{ route('admin.tickets.recalculate-duration') }}",
                    type: "POST",
                    data: {
                        ids: selectedTickets
                    },
                    success: (res) => {
                        Toast.fire({
                            icon: 'success',
                            title: 'Hitung ulang durasi berhasil'
                        });
                        dataGrid.refresh();
                    },
                    error: (error) => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hitung ulang durasi gagal'
                        });
                    }
                });
            });
        }

        $(document).ready(() => {
            getData();
            loadEvents();
        });
    </script>
@endsection
