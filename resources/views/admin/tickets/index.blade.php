@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12 d-flex justify-content-between">
            @can('ticket_create')
                <a class="btn btn-success" href="{{ route("admin.tickets.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.ticket.title_singular') }}
                </a>
            @endcan
            <a class="btn btn-primary" href="{{ route("admin.tickets.showReport") }}">
                Laporan
            </a>
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
                console.log(params);
                $.getJSON("{{ route('admin.workinglogs.data') }}", params)
                    .done(function(response) {
                        d.resolve(response.data, {
                            totalCount: response.totalCount,
                        });
                    })
                    .fail(function() { throw "Data loading error" });
                return d.promise();
            },
        });

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
    <script>
        $(function () {
            // let filters = `
            //     <form class="form-inline" id="filtersForm">
            //         <div class="form-group mx-sm-3 mb-2">
            //             <select class="form-control" name="status">
            //                 <option value="">Semua Status</option>
            //                 @foreach($statuses as $status)
            //                     <option value="{{ $status->id }}"{{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
            //                 @endforeach
            //             </select>
            //         </div>
            //     <div class="form-group mx-sm-3 mb-2">
            //         <select class="form-control" name="priority">
            //             <option value="">Semua Prioritas</option>
            //             @foreach($priorities as $priority)
            //                 <option value="{{ $priority->id }}"{{ request('priority') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
            //             @endforeach
            //         </select>
            //     </div>
            //     <div class="form-group mx-sm-3 mb-2">
            //         <select class="form-control" name="category">
            //             <option value="">Semua Kategori</option>
            //             @foreach($categories as $category)
            //                 <option value="{{ $category->id }}"{{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            //             @endforeach
            //         </select>
            //     </div>
            //     </form>`;

            $('.card-body').on('change', 'select', function() {
                $('#filtersForm').submit();
            });

            let dtButtons = [];
            @can('ticket_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.tickets.massDestroy') }}",
                    className: 'btn-danger',
                    action: function (e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function (entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected ') }}');
                            return
                        }

                        if (confirm('{{ trans('global.areYouSure ') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function () {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            let searchParams = new URLSearchParams(window.location.search)
            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: {
                    url: "{{ route('admin.tickets.index') }}",
                    data: {
                        'status': searchParams.get('status'),
                        'priority': searchParams.get('priority'),
                        'category': searchParams.get('category')
                    }
                },
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'title',
                        name: 'title',
                        render: function (data, type, row) {
                            if(row.attachment_count > 0){
                                return '<a href="' + row.view_link + '">' + data + ' (' + row.comments_count + ') <i class="fas fa-file-image"></i></a>';
                            } else {
                                return '<a href="' + row.view_link + '">' + data + ' (' + row.comments_count + ') </a>';
                            }

                        }
                    },
                    {
                        data: 'last_comment',
                        name: 'last_comment'
                    },
                    {
                        data: 'status_name',
                        name: 'status.name',
                        render: function (data, type, row) {
                            return '<span style="color:' + row.status_color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'priority_name',
                        name: 'priority.name',
                        render: function (data, type, row) {
                            return '<span style="color:' + row.priority_color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'category_name',
                        name: 'category.name',
                        render: function (data, type, row) {
                            return '<span style="color:' + row.category_color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'author_name',
                        name: 'author_name'
                    },
                    {
                        data: 'author_email',
                        name: 'author_email'
                    },
                    {
                        data: 'project_name',
                        name: 'project.name'
                    },
                    {
                        data: 'assigned_to_user_name',
                        name: 'assigned_to_user.name'
                    },
                    {
                        data: 'work_duration',
                        name: 'work_duration'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions ') }}'
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            };

            // $(".datatable-Ticket").one("preInit.dt", function () {
            //     $(".dataTables_filter").after(filters);
            // });
            tableToReload = $('.datatable-Ticket').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
            });
        });
    </script>
@endsection
