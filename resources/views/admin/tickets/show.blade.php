@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ticket.title') }}
    </div>

    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.created_at') }}
                        </th>
                        <td>
                            {{ $ticket->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.title') }}
                        </th>
                        <td>
                            {{ $ticket->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.content') }}
                        </th>
                        <td>
                            {!! $ticket->content !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.attachments') }}
                        </th>
                        <td>
                            @foreach($ticket->attachments as $attachment)
                                <a href="{{ $attachment->geturl() }}" target="_blank">{{ $attachment->file_name }}</a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.status') }}
                        </th>
                        <td>
                            {{ $ticket->status->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.priority') }}
                        </th>
                        <td>
                            {{ $ticket->priority->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.category') }}
                        </th>
                        <td>
                            {{ $ticket->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.author_name') }}
                        </th>
                        <td>
                            {{ $ticket->author_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.author_email') }}
                        </th>
                        <td>
                            {{ $ticket->author_email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.assigned_to_user') }}
                        </th>
                        <td>
                            {{ $ticket->assigned_to_user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticket.fields.comments') }}
                        </th>
                        <td>
                            @forelse ($ticket->comments as $comment)
                                <div class="row">
                                    <div class="col">
                                        <p class="font-weight-bold"><a href="mailto:{{ $comment->author_email }}">{{ $comment->author_name }}</a> ({{ $comment->created_at }})</p>
                                        <p>{{ $comment->comment_text }}</p>
                                    </div>
                                </div>
                                <hr />
                            @empty
                                <div class="row">
                                    <div class="col">
                                        <p>Tidak ada balasan</p>
                                    </div>
                                </div>
                                <hr />
                            @endforelse
                            <form action="{{ route('admin.tickets.storeComment', $ticket->id) }}" method="POST" id="add-comment">
                                @csrf
                                <div class="form-group">
                                    <label for="comment_text">Tinggalkan balasan</label>
                                    <textarea class="form-control" id="comment_text" name="comment_text" rows="3" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-block">@lang('global.submit')</button>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="btn" id="loading"></p>
                                    </div>
                                </div>
                                
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a class="btn btn-default my-2" href="{{ route('admin.tickets.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        @can('ticket_edit')
            <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-primary">
                @lang('global.edit') @lang('cruds.ticket.title_singular')
            </a>
        @endcan
        

        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#add-comment').one('submit', function() {
        $(this).find('button[type="submit"]').attr('disabled','disabled');
        $('#loading').html('Tunggu sebentar...');
    });
</script>
@endsection