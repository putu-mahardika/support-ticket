@forelse ($notifications as $notification)
    <a class="dropdown-item d-flex align-items-center notification" href="#">
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
        <div>
            <div class="small text-gray-500">
                {{ $notification->created_at->format('D, d M Y H:i') }}
            </div>
            <span class="font-weight-bold">
                {{ $notification->data['title'] . ' - ' . $notification->data['text'] }}
            </span>
        </div>
    </a>
@empty
    <span class="font-weight-bold text-center d-block my-2">
        You not have notifications yet
    </span>
@endforelse
