@extends('layouts.admin')
@section('content')
@can('comment_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.comments.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.comment.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.comment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div id="gridContainer"></div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        let dataGrid = null;
        let dataSource = new DevExpress.data.CustomStore({
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

                $.getJSON("{{ route('admin.comments.data') }}", params)
                    .done(function(response) {
                        d.resolve(response.data, {
                            totalCount: response.totalCount,
                        });
                    })
                    .fail(function() { throw "Data loading error" });
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
                        dataType: 'datetime',
                        format: 'yyyy-MM-dd HH:mm:ss'
                    },
                    {
                        dataField: 'ticket.code',
                        caption: 'Ticket Code',
                    },
                    {
                        dataField: 'ticket.title',
                        caption: 'Ticket',
                    },
                    {
                        dataField: 'author_name'
                    },
                    {
                        dataField: 'author_email',
                    },
                    {
                        dataField: 'comment_text',
                        cellTemplate: (cellElement, cellInfo) => {
                            let att = cellInfo.data.attachments.length > 0 ? ' <i class="fas fa-paperclip"></i>' : '';
                            cellElement.html(

                                // You can see strLimit function at resources/js/app.js
                                strLimit(cellInfo.value, 15) + att
                            );
                        }
                    },
                    {
                        caption: 'Actions',
                        dataField: 'id',
                        cellTemplate: (cellElement, cellInfo) => {
                            cellElement.html(
                                `<a class="btn btn-primary btn-sm" href="{{ route('admin.comments.index') }}/${cellInfo.value}">
                                    <i class="fas fa-eye fa-sm"></i>
                                </a>
                                <a class="btn btn-warning btn-sm" href="{{ route('admin.comments.index') }}/${cellInfo.value}/edit">
                                    <i class="fas fa-edit fa-sm"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" type="button" onclick="actionDelete(${cellInfo.value});">
                                    <i class="fas fa-trash-alt fa-sm"></i>
                                </button>`
                            );
                        },
                        allowFiltering: false,
                        allowSorting: false,
                        minWidth: 150,
                    }
                ],
                showBorders: true,
                filterRow: { visible: true },
                hoverStateEnabled: true,
                remoteOperations: {
                    paging: true,
                    filtering: true,
                    sorting: true,
                },
                wordWrapEnabled: true
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
                        url: `{{ route('admin.comments.index') }}/${id}`,
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

        $(document).ready(() => {
            getData();
        });

    </script>
@endsection
