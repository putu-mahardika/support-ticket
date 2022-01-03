@extends('layouts.admin')
@section('content')
@can('role_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.roles.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div id="gridContainer"></div>

        {{-- <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Role">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.role.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.permissions') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $key => $role)
                        <tr data-entry-id="{{ $role->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $role->id ?? '' }}
                            </td>
                            <td>
                                {{ $role->title ?? '' }}
                            </td>
                            <td>
                                @foreach($role->permissions as $key => $item)
                                    <span class="badge badge-info">{{ $item->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                @can('role_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.roles.show', $role->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('role_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.roles.edit', $role->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('role_delete')
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> --}}


    </div>
</div>
@endsection
@section('scripts')
    <script>
        let dataGrid = null;

        function getData() {
            dataGrid = $("#gridContainer").dxDataGrid({
                dataSource: @json($roles),
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
                        dataField: "title",
                        minWidth: 150,
                    },
                    {
                        dataField: 'permissions',
                        cellTemplate: (cellElement, cellInfo) => {
                            let values = cellInfo.value.map(permission => {
                                return `<span class="badge badge-info">${permission.title}</span>`;
                            });
                            cellElement.html(values.join(' '));
                        }
                    },
                    {
                        caption: '',
                        dataField: 'id',
                        cellTemplate: (cellElement, cellInfo) => {
                            cellElement.html(
                                `<a class="btn btn-warning btn-sm" href="{{ route('admin.roles.index') }}/${cellInfo.value}/edit">
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
                        url: `{{ route('admin.roles.index') }}/${id}`,
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
