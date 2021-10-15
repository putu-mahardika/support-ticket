@extends('layouts.admin')
@section('content')
    <div class="d-flex mb-2 justify-content-end">
        <a href="#">Marks All Read</a>
    </div>
    @forelse($notifications as $date => $val)
        <h5>
            <span class="badge badge-primary py-2 px-3 shadow-sm">
                {{ $date }}
            </span>
        </h5>
        <div class="list-group mb-5 shadow-sm">
            @foreach ($val as $notification)
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex px-3 py-2">
                        <div class="mr-3">
                            <div class="icon-circle bg-primary position-relative">
                                @if ($notification->type == 'App\Notifications\TicketNotification')
                                    <i class="fas fa-ticket-alt text-white"></i>
                                @elseif ($notification->type == 'App\Notifications\CommentNotification')
                                    <i class="fas fa-comment-dots"></i>
                                @endif
                                @if(empty($notification->read_at))
                                    <span class="badge badge-danger position-absolute" style="top: 0; left: -1rem;">
                                        new
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <div class="font-weight-bold">
                                {{ $notification->data['title'] . ' - ' . $notification->data['text'] }}
                            </div>
                            <span class="small text-muted">
                                {{ $notification->data['ticket_code'] . ' - ' . $notification->data['ticket_title'] }}
                            </span>
                        </div>
                        <div class="small text-muted">
                            {{ $notification->created_at->format('H:i') }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @empty
        There are no new notifications
    @endforelse
@endsection
