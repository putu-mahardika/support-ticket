@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.comment.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.comments.update", [$comment->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('ticket_id') ? 'has-error' : '' }}">
                <label for="ticket">{{ trans('cruds.comment.fields.ticket') }}</label>
                <select name="ticket_id" id="ticket" class="form-control select2">
                    @foreach($tickets as $id => $ticket)
                        <option value="{{ $id }}" {{ (isset($comment) && $comment->ticket ? $comment->ticket->id : old('ticket_id')) == $id ? 'selected' : '' }}>{{ $ticket }}</option>
                    @endforeach
                </select>
                @if($errors->has('ticket_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ticket_id') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('author_name') ? 'has-error' : '' }}">
                <label for="author_name">{{ trans('cruds.comment.fields.author_name') }}*</label>
                <input type="text" id="author_name" name="author_name" class="form-control" value="{{ old('author_name', isset($comment) ? $comment->author_name : '') }}" required>
                @if($errors->has('author_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('author_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.comment.fields.author_name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('author_email') ? 'has-error' : '' }}">
                <label for="author_email">{{ trans('cruds.comment.fields.author_email') }}*</label>
                <input type="text" id="author_email" name="author_email" class="form-control" value="{{ old('author_email', isset($comment) ? $comment->author_email : '') }}" required>
                @if($errors->has('author_email'))
                    <em class="invalid-feedback">
                        {{ $errors->first('author_email') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.comment.fields.author_email_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                <label for="user">{{ trans('cruds.comment.fields.user') }}</label>
                <select name="user_id" id="user" class="form-control select2">
                    @foreach($users as $id => $user)
                        <option value="{{ $id }}" {{ (isset($comment) && $comment->user ? $comment->user->id : old('user_id')) == $id ? 'selected' : '' }}>{{ $user }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('user_id') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('comment_text') ? 'has-error' : '' }}">
                <label for="comment_text">{{ trans('cruds.comment.fields.comment_text') }}*</label>
                <textarea id="comment_text" name="comment_text" class="form-control " required>{{ old('comment_text', isset($comment) ? $comment->comment_text : '') }}</textarea>
                @if($errors->has('comment_text'))
                    <em class="invalid-feedback">
                        {{ $errors->first('comment_text') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.comment.fields.comment_text_helper') }}
                </p>
            </div>

            {{-- Comment Attachment Section --}}
            <div class="form-group {{ $errors->has('attachments') ? 'has-error' : '' }}">
                <label for="attachments">Attachments</label>

                {{-- id attachments-comment-dropzone is important for JS. please check the function: dropzoneInit() --}}
                <div class="needsclick dropzone" id="attachments-comment-dropzone"></div>

                @if($errors->has('attachments'))
                    <em class="invalid-feedback">
                        {{ $errors->first('attachments') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.ticket.fields.attachments_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        var uploadedAttachmentsMap = {}
        // When this page is fully loaded, everything in this function will run
        $(document).ready(() => {
            dropzoneInit();
        });


        /**
         * Init dropzone
         */
        function dropzoneInit() {
            $("#attachments-comment-dropzone").dropzone({
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
                    $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">');
                    uploadedAttachmentsMap[file.name] = response.name;
                },
                removedfile: function (file) {
                    file.previewElement.remove();
                    var name = '';
                    if (typeof file.file_name !== 'undefined') {
                        name = file.file_name;
                    }
                    else {
                        name = uploadedAttachmentsMap[file.name];
                    }
                    $('form').find('input[name="attachments[]"][value="' + name + '"]').remove();
                },
                init: function () {
                    @if(!empty($comment->attachments))

                        // show image attachments
                        var files = {!!json_encode(FunctionHelper::getImagesAttachment($comment->attachments))!!};
                        for (var i in files) {
                            var file = files[i];
                            this.options.addedfile.call(this, file);
                            file.previewElement.classList.add('dz-complete');

                            // Show image as thumbnail
                            $(file.previewElement).find('.dz-image img')[0].setAttribute('src', file.custom_properties.url);

                            // add file name to form
                            $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name + '">');
                        }

                        // show non-image attachments
                        files = {!!json_encode(FunctionHelper::getImagesAttachment($comment->attachments, true))!!};
                        for (var i in files) {
                            var file = files[i];
                            this.options.addedfile.call(this, file);
                            file.previewElement.classList.add('dz-complete');

                            // add file name to form
                            $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name + '">');
                        }
                    @endif
                },
                error: function (file, response) {
                    if ($.type(response) === 'string') {
                        var message = response; //dropzone sends it's own error messages in string
                    }
                    else {
                        var message = response.errors.file;
                    }
                    file.previewElement.classList.add('dz-error');
                    _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]');
                    _results = [];
                    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                        node = _ref[_i];
                        _results.push(node.textContent = message);
                    }

                    return _results;
                }
            });
        }
    </script>
@endsection
