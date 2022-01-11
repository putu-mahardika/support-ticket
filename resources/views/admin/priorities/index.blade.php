@extends('layouts.admin')
@section('content')
    @can('priority_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.priorities.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.priority.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.priority.title_singular') }} {{ trans('global.list') }}
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
                dataSource: @json($priorities),
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
                        dataField: "name",
                        minWidth: 150,
                    },
                    {
                        dataField: 'color',
                        cellTemplate: (cellElement, cellInfo) => {
                            cellElement.css('background-color', cellInfo.value);
                        }
                    },
                    {
                        dataField: 'icon',
                        cellTemplate: (cellElement, cellInfo) => {
                            cellElement.addClass('text-center')
                            cellElement.html(`<i class="${cellInfo.value} fa-2x"></i>`);
                        }
                    },
                    {
                        caption: '',
                        dataField: 'id',
                        cellTemplate: (cellElement, cellInfo) => {
                            cellElement.html(
                                `<a class="btn btn-warning btn-sm" href="{{ route('admin.priorities.index') }}/${cellInfo.value}/edit">
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
                remoteOperations: {
                    paging: true,
                    filtering: true
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
                        url: `{{ route('admin.priorities.index') }}/${id}`,
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
