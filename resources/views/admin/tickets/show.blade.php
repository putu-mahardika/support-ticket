@extends('layouts.admin')

@section('styles')
    <style>
        /* width */
        #quick-reply::-webkit-scrollbar {
            width: 5px;
            height: 7px;
        }

        /* Track */
        #quick-reply::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        #quick-reply::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        #quick-reply::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .reply {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

{{-- Detail ticket --}}
<div class="card">
    <div class="card-header d-flex justify-content-between">
        {{ trans('global.show') }} {{ trans('cruds.ticket.title_singular') }}

        @can('ticket_edit')
            <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-primary btn-sm">
                @lang('global.edit') @lang('cruds.ticket.title_singular')
            </a>
        @endcan
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
                    <form id="formQuickEditTicket" action="{{ route("admin.tickets.update", [$ticket->id]) }}" method="post">
                        @csrf
                        @method('PUT')
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
                                {{ trans('cruds.ticket.fields.code') }}
                            </th>
                            <td>
                                {{ $ticket->code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.ticket.fields.title') }}
                            </th>
                            <td>
                                @can ('ticket_edit')
                                    <input type="text" name="title" id="title" value="{{ $ticket->title }}" class="form-control">
                                @else
                                    {{ $ticket->title }}
                                @endcan
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.ticket.fields.content') }}
                            </th>
                            <td>
                                @can ('ticket_edit')
                                    <textarea name="content" id="content" cols="30" rows="5" class="form-control" style="resize: none;">{!! $ticket->content !!}</textarea>
                                @else
                                    {!! $ticket->content !!}
                                @endcan
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Ticket Ref
                            </th>
                            <td>
                                @can ('ticket_edit')
                                    <select name="ref_id" id="ref_id" class="form-control select2">
                                        <option value="">-- None --</option>
                                        @foreach ($ticketRef as $item)
                                            <option value="{{ $item->id }}" @if($ticket->ref_id == $item->id) selected @endif>{{ $item->code }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    {{ $ticket->ref->code ?? '-' }}
                                @endcan
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.ticket.fields.attachments') }}
                            </th>
                            <td>
                                <div class="row ">
                                    @foreach($ticket->attachments as $attachment)
                                        @php
                                        $file_ext = pathinfo($attachment->geturl(), PATHINFO_EXTENSION);
                                        @endphp
                                        <div class="col-lg-4 mb-3">
                                        @if (in_array($file_ext, ['jpg', 'JPG', 'jpeg', 'JPEG', 'gif', 'GIF']))
                                            <a href="{{ $attachment->geturl() }}" target="_blank"><img src="{{ $attachment->geturl() }}" alt="thumbnail" width="100" height="100">{{ $attachment->file_name }}</a>
                                        @elseif (in_array($file_ext, ['doc', 'DOC', 'docx', 'docx']))
                                            <a href="{{ $attachment->geturl() }}" target="_blank"><img src="{{ asset('images/word.png') }}" alt="thumbnail" width="100" height="100">{{ $attachment->file_name }}</a>
                                        @elseif (in_array($file_ext, ['xls', 'XLS', 'xlsx', 'XLSX']))
                                            <a href="{{ $attachment->geturl() }}" target="_blank"><img src="{{ asset('images/excel.png') }}" alt="thumbnail" width="100" height="100">{{ $attachment->file_name }}</a>
                                        @else
                                            <a href="{{ $attachment->geturl() }}" target="_blank"><img src="{{ asset('images/paper.png') }}" alt="thumbnail" width="100" height="100">{{ $attachment->file_name }}</a>
                                        @endif
                                        </div>
                                    @endforeach
                                </div>
                                
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.ticket.fields.status') }}
                            </th>
                            <td>
                                @can ('ticket_edit')
                                    <select class="form-control" name="status_id" id="status" style="width: max-content;">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}" @if($ticket->status->id == $status->id) selected @endif>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    {{ $ticket->status->name ?? '' }}
                                @endcan
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.ticket.fields.priority') }}
                            </th>
                            <td>
                                @can ('ticket_edit')
                                    <select class="form-control" name="priority_id" id="priority" style="width: max-content;">
                                        @foreach ($priorities as $priority)
                                            <option value="{{ $priority->id }}" @if($ticket->priority_id == $priority->id) selected @endif>{{ $priority->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    {{ $ticket->priority->name ?? '' }}
                                @endcan
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.ticket.fields.category') }}
                            </th>
                            <td>
                                @can ('ticket_edit')
                                    <select class="form-control" name="category_id" id="category" style="width: max-content;">
                                        <option value="">-- None --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if($ticket->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    {{ $ticket->category->name ?? '-' }}
                                @endcan
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
                        @if (!empty($ticket->work_start))
                            <tr>
                                <th>
                                    Work Start
                                </th>
                                <td>
                                    {{ $ticket->work_start }}
                                </td>
                            </tr>
                        @endif
                        @if (!empty($ticket->work_end))
                            <tr>
                                <th>
                                    Work End
                                </th>
                                <td>
                                    {{ $ticket->work_end }}
                                </td>
                            </tr>
                        @endif
                        @if (!empty($ticket->work_duration))
                            <tr>
                                <th>
                                    Work Duration
                                </th>
                                <td>
                                    {{ gmdate('H \j\a\m i \m\e\n\i\t', $ticket->work_duration) }}
                                </td>
                            </tr>
                        @endif
                    </form>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between">
            <a class="btn btn-default my-2" href="{{ route('admin.tickets.index') }}">
                {{ trans('global.back_to_list') }}
            </a>

            @can('ticket_edit')
                <button type="submit" id="btnSaveTicket" form="formQuickEditTicket" class="btn btn-primary my-2 px-5 d-none">
                    Save
                </button>
            @endcan
        </div>
    </div>
</div>

{{-- Comments --}}
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.comments') }}
                </th>
                <td>
                    <div class="row">
                        <div class="col">
                            @forelse ($ticket->comments as $comment)
                                <p class="font-weight-bold">
                                    <a href="mailto:{{ $comment->author_email }}">{{ $comment->author_name }}</a> ({{ $comment->created_at }})
                                </p>
                                <p>{{ $comment->comment_text }}</p>
                                <hr>
                            @empty
                                <p>Tidak ada balasan</p>
                            @endforelse
                        </div>
                    </div>
                    <form action="{{ route('admin.tickets.storeComment', $ticket->id) }}" method="POST" id="add-comment">
                        @csrf
                        <div class="form-group mt-3">
                            <label for="comment_text">Tinggalkan balasan</label>
                            @if (!auth()->user()->hasRole('client'))
                                <div class="bg-white border rounded" style="min-height: 10rem">
                                    <div class="card mb-0 border-right-0 border-left-0 border-top-0 rounded-0">
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <select class="form-control rounded-0 border-0" name="status_comment" id="status_comment" style="width: max-content;">
                                                        <option value="">-- None --</option>
                                                        @foreach ($statuses as $status)
                                                            <option value="{{ $status->id }}" @if($ticket->status->id == 1 && $status->id == 3) selected @endif>{{ $status->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 p-1">
                                                    <div id="quick-reply" class="p-1" style="width: 40rem; overflow-x: auto; white-space: nowrap; float: left;">
                                                        <span class="bg-light rounded border p-1 reply">Baik, Kami kerjakan</span>
                                                        <span class="bg-light rounded border p-1 reply">Terima kasih</span>
                                                        <span class="bg-light rounded border p-1 reply">Tiket sudah selesai ditangani, mohon dicek.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea class="form-control border-0 shadow-none" style="resize: none;" id="comment_text" name="comment_text" rows="3" required></textarea>
                                </div>
                            @else
                                <textarea class="form-control" style="resize: none;" id="comment_text" name="comment_text" rows="3" required></textarea>
                            @endif
                            
                            <div class="form-group {{ $errors->has('attachments') ? 'has-error' : '' }}">
                                <label for="attachments">{{ trans('cruds.ticket.fields.attachments') }}</label>
                                <div class="needsclick dropzone" id="attachments-dropzone">

                                </div>
                                @if($errors->has('attachments'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('attachments') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.ticket.fields.attachments_helper') }}
                                </p>
                            </div>
                        <div class="row">
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-block">@lang('global.submit')</button>
                            </div>
                            <div class="col-md-4">
                                <p class="btn" id="loading"></p>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>

    @can ('ticket_edit')
        let ticketIsChange = false;
        $('.reply').on('click', function () {
            $('#comment_text').val(
                $('#comment_text').val() + $(this).html()
            );
        });

        $('#title').on('change', function () {
            switchToEditMode();
        });

        $('#content').on('change', function () {
            switchToEditMode();
        });

        $('#status').on('change', function () {
            switchToEditMode();
        });

        $('#priority').on('change', function () {
            switchToEditMode();
        });

        $('#category').on('change', function () {
            switchToEditMode();
        });

        $('#ref_id').on('change', function (e) {
            switchToEditMode();
        });

        function switchToEditMode() {
            ticketIsChange = true;
            if (ticketIsChange) {
                $('#btnSaveTicket').removeClass('d-none');
            }
        }
    @endcan

    $('#add-comment').on('submit', function() {
        $(this).find('button[type="submit"]').attr('disabled','disabled');
        $('#loading').html('Tunggu sebentar...');
    });
</script>
<script>
    var uploadedAttachmentsMap = {}
Dropzone.options.attachmentsDropzone = {
    url: '{{ route('admin.tickets.storeMedia') }}',
    maxFilesize: 2, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">')
      uploadedAttachmentsMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttachmentsMap[file.name]
      }
      $('form').find('input[name="attachments[]"][value="' + name + '"]').remove()
    },
    init: function () {
        @if(isset($ticket) && $ticket->attachments)
                var files = null;
                    
        @endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection
