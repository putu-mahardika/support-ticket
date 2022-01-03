@extends('layouts.admin')
@section('content')
@can('permission_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.permissions.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.permission.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div id="gridContainer"></div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        let dataGrid = null;

        function getData() {
            dataGrid = $("#gridContainer").dxDataGrid({
                dataSource: @json($permissions),
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
                    { dataField: "title" },
                    {
                        caption: '',
                        dataField: 'id',
                        cellTemplate: (cellElement, cellInfo) => {
                            cellElement.html(
                                `<a class="btn btn-warning btn-sm" href="{{ route('admin.permissions.index') }}/${cellInfo.value}/edit">
                                    <i class="fas fa-edit fa-sm"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" type="button" onclick="actionDelete(${cellInfo.value});">
                                    <i class="fas fa-trash-alt fa-sm"></i>
                                </button>`
                            );
                        },
                        allowFiltering: false,
                        allowSorting: false,
                    }
                ],
                showBorders: true,
                filterRow: { visible: true },
                hoverStateEnabled: true,
                remoteOperations: {
                    paging: true,
                    filtering: true
                }
            }).dxDataGrid("instance");
        };

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
                        url: `{{ route('admin.permissions.index') }}/${id}`,
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
