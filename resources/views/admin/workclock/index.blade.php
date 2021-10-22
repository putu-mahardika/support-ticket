@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.workclock.create') }}">
                Create Clock
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Workclock List
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th class="text-right">
                                #
                            </th>
                            <th>
                                Day
                            </th>
                            <th>
                                Start
                            </th>
                            <th>
                                Duration
                            </th>
                            <th>
                                End
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workclocks as $workclock)
                            <tr>
                                <td class="text-right">
                                    {{ $loop->iteration ?? '' }}
                                </td>
                                <td>
                                    {{ $workclock->day ?? '' }}
                                </td>
                                <td>
                                    {{ $workclock->time_start }}
                                </td>
                                <td>
                                    {{ $workclock->duration ?? '' }}
                                </td>
                                <td>
                                    {{ Carbon\Carbon::create($workclock->time_start)->addHours($workclock->duration)->format('H:i:s') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.workclock.edit', $workclock->id) }}" class="btn btn-sm btn-info">
                                        Edit
                                    </a>
                                    <button class="btn btn-sm btn-danger" id="btnDelete" data-id="{{ $workclock->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    No data!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            $('body').on('click', '#btnDelete', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: `{{ route('admin.workclock.index') }}/${id}`,
                            success: function(response) {
                                window.location.reload();
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection
