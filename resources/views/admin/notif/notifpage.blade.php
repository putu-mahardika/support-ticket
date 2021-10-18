@extends('layouts.admin')
@section('content')
    <div class="d-flex mb-2 justify-content-end">
        <button type="button" class="btn btn-default text-primary" onclick="markReadAll(this)">
            <div class="spinner-border spinner-border-sm align-middle d-none" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Marks All Read
        </button>
    </div>
    @forelse($notifications as $date => $val)
        <h5>
            <span class="badge badge-primary py-2 px-3 shadow-sm">
                {{ $date }}
            </span>
        </h5>
        <div class="list-group mb-5 shadow-sm notif-list">
            @foreach ($val as $notification)
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex px-3 py-2">
                        <div class="mr-3">
                            <div class="icon-circle bg-primary position-relative">
                                @if (Str::contains($notification->type, 'Ticket'))
                                    <i class="fas fa-ticket-alt text-white"></i>
                                @elseif (Str::contains($notification->type, 'Comment'))
                                    <i class="fas fa-comment-dots text-white"></i>
                                @endif
                                @if(empty($notification->read_at))
                                    <span id="badge_{{ $notification->id }}" class="badge badge-danger position-absolute" style="top: 0; left: -1rem;">
                                        new
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col text-truncate">
                            <div class="font-weight-bold">
                                @if (Str::contains($notification->type, 'Ticket'))
                                    {{ $notification->data['title'] . ' - ' . $notification->data['text'] }}
                                @elseif (Str::contains($notification->type, 'Comment'))
                                    {{ $notification->data['title'] }}
                                @endif
                            </div>
                            @if (Str::contains($notification->type, 'Ticket'))
                                @if (isset($notification->data['ticket_code']) && isset($notification->data['ticket_title']))
                                    <span class="small text-muted">
                                        {{ $notification->data['ticket_code'] . ' - ' . $notification->data['ticket_title'] }}
                                    </span>
                                @endif
                            @elseif (Str::contains($notification->type, 'Comment'))
                                <span class="small text-muted">
                                    {{ $notification->data['comment_text'] }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <span class="small text-muted d-block text-right">
                                {{ $notification->created_at->format('H:i') }}
                            </span>
                            @if (empty($notification->read_at))
                                <button type="button" onclick="markRead('{{ $notification->id }}');" class="btn btn-primary btn-sm rounded-pill py-0 px-2 d-none d-md-block {{ $notification->id }}">
                                    <div class="loading spinner-border spinner-border-sm text-white align-middle d-none" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    Mark Read
                                </button>
                            @endif
                        </div>
                    </div>
                    @if (empty($notification->read_at))
                        <button type="button" onclick="markRead('{{ $notification->id }}');" class="btn btn-primary btn-sm btn-block my-2 rounded-pill d-block d-md-none py-0 px-2 {{ $notification->id }}">
                            <div class="loading spinner-border spinner-border-sm text-white align-middle d-none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Mark Read
                        </button>
                    @endif
                </a>
            @endforeach
        </div>
    @empty
        There are no new notifications
    @endforelse
@endsection

@section('scripts')
    <script>
        function markRead(id) {
            let buttons = $(`.${id}`);
            $.each(buttons, (key, button) => {
                $(button).addClass('disabled');
                $(button).children().toggleClass('d-none');
            });
            $.ajax({
                type: "POST",
                url: "{{ route('admin.notif.markRead') }}",
                data: {id: id},
                success: function(response) {
                    $.each(buttons, (key, button) => {
                        $(button).children().toggleClass('d-none');
                        if ($(button).hasClass('d-none')) {
                            $(button).removeClass('d-md-block');
                        }
                        else {
                            $(button).removeClass('d-block d-md-none');
                            $(button).addClass('d-none');
                            $(`#badge_${id}`).addClass('d-none');
                        }
                    });
                    reloadNotification();
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function markReadAll(btn) {
            let button = $(btn);
            button.prop('disabled', true);
            button.children().removeClass('d-none');
            $.ajax({
                type: "POST",
                url: "{{ route('admin.notif.markRead') }}",
                data: {selectAll: true},
                success: function(response) {
                    location.reload();
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    </script>
@endsection
