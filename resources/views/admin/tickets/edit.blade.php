@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.ticket.title_singular') }}
        </div>

        @if(session('status'))
            <div class="alert alert-danger" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="card-body">
            <form id="edit-ticket" action="{{ route("admin.tickets.update", [$ticket->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="title">{{ trans('cruds.ticket.fields.title') }}*</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', isset($ticket) ? $ticket->title : '') }}" required>
                    @if($errors->has('title'))
                        <em class="invalid-feedback">
                            {{ $errors->first('title') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.ticket.fields.title_helper') }}
                    </p>
                </div>

                <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                    <label for="content">{{ trans('cruds.ticket.fields.content') }}</label>
                    <textarea id="content" name="content" class="form-control ">{{ old('content', isset($ticket) ? $ticket->content : '') }}</textarea>
                    @if($errors->has('content'))
                        <em class="invalid-feedback">
                            {{ $errors->first('content') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.ticket.fields.content_helper') }}
                    </p>
                </div>

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

                <div class="form-group {{ $errors->has('status_id') ? 'has-error' : '' }}">
                    <label for="status">{{ trans('cruds.ticket.fields.status') }}*</label>
                    <select name="status_id" id="status" class="form-control select2" required>
                        @foreach($statuses as $id => $status)
                            <option value="{{ $id }}" {{ (isset($ticket) && $ticket->status ? $ticket->status->id : old('status_id')) == $id ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('status_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('status_id') }}
                        </em>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('priority_id') ? 'has-error' : '' }}">
                    <label for="priority">{{ trans('cruds.ticket.fields.priority') }}*</label>
                    <select name="priority_id" id="priority" class="form-control select2" required>
                        @foreach($priorities as $id => $priority)
                            <option value="{{ $id }}" {{ (isset($ticket) && $ticket->priority ? $ticket->priority->id : old('priority_id')) == $id ? 'selected' : '' }}>{{ $priority }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('priority_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('priority_id') }}
                        </em>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                    <label for="category">{{ trans('cruds.ticket.fields.category') }}*</label>
                    <select name="category_id" id="category" class="form-control select2" required>
                        @foreach($categories as $id => $category)
                            <option value="{{ $id }}" {{ (isset($ticket) && $ticket->category ? $ticket->category->id : old('category_id')) == $id ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('category_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('category_id') }}
                        </em>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('author_name') ? 'has-error' : '' }}">
                    <label for="author_name">{{ trans('cruds.ticket.fields.author_name') }}</label>
                    <input type="text" id="author_name" name="author_name" class="form-control" value="{{ old('author_name', isset($ticket) ? $ticket->author_name : '') }}">
                    @if($errors->has('author_name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('author_name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.ticket.fields.author_name_helper') }}
                    </p>
                </div>

                <div class="form-group {{ $errors->has('author_email') ? 'has-error' : '' }}">
                    <label for="author_email">{{ trans('cruds.ticket.fields.author_email') }}</label>
                    <input type="text" id="author_email" name="author_email" class="form-control" value="{{ old('author_email', isset($ticket) ? $ticket->author_email : '') }}">
                    @if($errors->has('author_email'))
                        <em class="invalid-feedback">
                            {{ $errors->first('author_email') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.ticket.fields.author_email_helper') }}
                    </p>
                </div>

                @if(auth()->user()->isAdmin())
                    <div class="form-group {{ $errors->has('assigned_to_user_id') ? 'has-error' : '' }}">
                        <label for="assigned_to_user">{{ trans('cruds.ticket.fields.assigned_to_user') }}</label>
                        <select name="assigned_to_user_id" id="assigned_to_user" class="form-control select2">
                            @foreach($assigned_to_users as $id => $assigned_to_user)
                                <option value="{{ $id }}" {{ (isset($ticket) && $ticket->assigned_to_user ? $ticket->assigned_to_user->id : old('assigned_to_user_id')) == $id ? 'selected' : '' }}>{{ $assigned_to_user }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('assigned_to_user_id'))
                            <em class="invalid-feedback">
                                {{ $errors->first('assigned_to_user_id') }}
                            </em>
                        @endif
                    </div>
                @endif

                @if (auth()->user()->isAdmin() && $ticket->status_id == 5)
                    <div class="row my-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <label for="checkbox_edit_work_start">Work Start</label>
                                        <input type="checkbox" class="form-checkbox m-1" name="checkbox_edit_work_start" id="checkbox_edit_work_start" onchange="dateBackToogle('start')">
                                    </div>
                                    <hr>
                                    <div id="work_start_container" class="disabledContainer">
                                        <div class="form-group">
                                            <label for="old_work_start">Old</label>
                                            <input type="datetime-local" class="form-control" name="old_work_start" id="old_work_start" value="{{ old('old_work_start', Str::replace(' ', 'T', $ticket->work_start)) }}" readonly>
                                        </div>
                                        <div class="form-group {{ $errors->has('work_start') ? 'has-error' : '' }}">
                                            <label for="work_start">New<span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" name="work_start" id="work_start">
                                            @if($errors->has('work_start'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('work_start') }}
                                                </em>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('work_start_reason') ? 'has-error' : '' }}">
                                            <label for="work_start_reason">Reason<span class="text-danger">*</span></label>
                                            <textarea style="resize: none;" class="form-control" name="work_start_reason" id="work_start_reason" cols="30" rows="3"></textarea>
                                            @if($errors->has('work_start_reason'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('work_start_reason') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <label for="checkbox_edit_work_end">Work End</label>
                                        <input type="checkbox" class="form-checkbox m-1" name="checkbox_edit_work_end" id="checkbox_edit_work_end" onchange="dateBackToogle('end')">
                                    </div>
                                    <hr>
                                    <div id="work_end_container" class="disabledContainer">
                                        <div class="form-group">
                                            <label for="old_work_end">Old</label>
                                            <input type="datetime-local" class="form-control" name="old_work_end" id="old_work_end" value="{{ old('old_work_end', Str::replace(' ', 'T', $ticket->work_end)) }}" readonly>
                                        </div>
                                        <div class="form-group {{ $errors->has('work_end') ? 'has-error' : '' }}">
                                            <label for="work_end">New<span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" name="work_end" id="work_end">
                                            @if($errors->has('work_end'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('work_end') }}
                                                </em>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('work_end_reason') ? 'has-error' : '' }}">
                                            <label for="work_end_reason">Reason<span class="text-danger">*</span></label>
                                            <textarea style="resize: none;" class="form-control" name="work_end_reason" id="work_end_reason" cols="30" rows="3"></textarea>
                                            @if($errors->has('work_end_reason'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('work_end_reason') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-1">
                        <input class="btn btn-danger btn-block" type="submit" value="{{ trans('global.save') }}">
                    </div>
                    <div class="col-2">
                        <p class="btn" id="loading"></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var uploadedAttachmentsMap = {};
        function dropzoneInit() {
            $("#attachments-dropzone").dropzone({
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
                    @if(isset($ticket) && $ticket->attachments)
                        var files = {!!json_encode($ticket->attachments)!!};
                        for (var i in files) {
                            var file = files[i];
                            this.options.addedfile.call(this, file);
                            file.previewElement.classList.add('dz-complete');
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

        function loadEvents() {
            $('#edit-ticket').on('submit', function() {
                $(this).find('input[type="submit"]').attr('disabled','disabled');
                $('#loading').html('Tunggu sebentar...');
            });
        }

        $(document).ready(() => {
            dropzoneInit();
            loadEvents();
        });

        function dateBackToogle(name) {
            $(`#work_${name}_container`).toggleClass('disabledContainer');
            $(`#work_${name}`).prop('required', !$(`#work_${name}`).prop('required'));
            $(`#work_${name}_reason`).prop('required', !$(`#work_${name}_reason`).prop('required'));
        }
    </script>
@endsection
