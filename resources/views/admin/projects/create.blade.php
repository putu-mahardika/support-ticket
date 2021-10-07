@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.project.title_singular') }}
    </div>

    <div class="card-body">
        <form id="add-project" action="{{ route("admin.projects.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                <label for="code">{{ trans('cruds.project.fields.code') }}*</label>
                <input type="text" id="code" name="code" class="form-control" value="" required>
                @if($errors->has('code'))
                    <em class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.code_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.project.fields.title') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="" autofocus required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.title_helper') }}
                </p>
            </div>
            @if(auth()->user()->isAdmin())
                <div class="form-group {{ $errors->has('assign_user_id') ? 'has-error' : '' }}">
                    <label for="assigned_to_user">{{ trans('cruds.ticket.fields.assigned_to_user') }}</label>
                    <select name="assign_user_id" id="assigned_to_user" class="form-control select2">
                        @foreach($assigned_to_users as $id => $assigned_to_user)
                            <option value="{{ $id }}" {{ (isset($project) && $project->user->first()->pivot->user_id ? $ticket->assigned_to_user->id : old('assigned_to_user_id')) == $id ? 'selected' : '' }}>{{ $assigned_to_user }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('assign_to_user_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('assign_user_id') }}
                        </em>
                    @endif
                </div>
            @endif
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
            <div class="col-md-4">
                <p class="btn" id="loading"></p>
            </div>
        </form>


    </div>
</div>
@endsection

@section('scripts')
    <script>
        $('body').on('keyup', '#name', function () {
            $('#code').val(
                getInitials($(this).val().trim())
            );
        });

        $('body').on('keyup', '#code', function () {
            $(this).val(
                $(this).val().toUpperCase()
            );
        });
        $('#add-project').on('submit', function() {
            $(this).find('input[type="submit"]').attr('disabled','disabled');
            $('#loading').html('Tunggu sebentar...');
        });
    </script>
@endsection
