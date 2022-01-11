@extends('layouts.admin')
@section('content')
@can('user_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.users.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
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
                dataSource: @json($users),
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
                    },
                    {
                        dataField: "email"
                    },
                    {
                        dataField: "company"
                    },
                    {
                        dataField: 'projects',
                        cellTemplate: (cellElement, cellInfo) => {
                            let values = cellInfo.value.map(project => {
                                return `<span class="badge badge-primary">${project.name}</span>`;
                            });
                            cellElement.html(values.join(' '));
                        }
                    },
                    {
                        dataField: 'roles',
                        cellTemplate: (cellElement, cellInfo) => {
                            let values = cellInfo.value.map(role => {
                                return `<span class="badge badge-info">${role.title}</span>`;
                            });
                            cellElement.html(values.join(' '));
                        }
                    },
                    {
                        caption: '',
                        dataField: 'id',
                        cellTemplate: (cellElement, cellInfo) => {
                            cellElement.html(
                                `<a class="btn btn-warning btn-sm" href="{{ route('admin.users.index') }}/${cellInfo.value}/edit">
                                    <i class="fas fa-edit fa-sm"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" type="button" onclick="actionDelete(${cellInfo.value});">
                                    <i class="fas fa-trash-alt fa-sm"></i>
                                </button>`
                            );
                        },
                        allowFiltering: false,
                        allowSorting: false,
                        minWidth: 50,
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

        $(document).ready(() => {
            getData();
        });
    </script>
@endsection
