@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.users.update", [$user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                @if($errors->has('name'))
                <em class="invalid-feedback">
                    {{ $errors->first('name') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">{{ trans('cruds.user.fields.email') }}*</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                @if($errors->has('email'))
                <em class="invalid-feedback">
                    {{ $errors->first('email') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">
                <label for="company">{{ trans('cruds.user.fields.company') }}</label>
                <input type="text" id="company" name="company" class="form-control" value="{{ old('name', isset($user) ? $user->company : '') }}">
                @if($errors->has('company'))
                <em class="invalid-feedback">
                    {{ $errors->first('company') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.company_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('projects') ? 'has-error' : '' }}">
                <label for="projects">{{ trans('cruds.user.fields.project') }}*
                    <button type="button" onclick="selectAll('projects')" class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</button>
                    <button type="button" onclick="diselectAll('projects')" class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</button>
                </label>
                <select name="projects[]" id="projects" class="form-control select2" multiple required>
                    <option value=""></option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ (in_array($project->id, old('projects', [])) || isset($user) && $user->projects->contains($project->id)) ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('projects'))
                <em class="invalid-feedback">
                    {{ $errors->first('projects') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.project_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input type="password" id="password" name="password" class="form-control">
                @if($errors->has('password'))
                <em class="invalid-feedback">
                    {{ $errors->first('password') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                <label for="roles">{{ trans('cruds.user.fields.roles') }}*
                    <button type="button" onclick="selectAll('roles')" class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</button>
                    <button type="button" onclick="diselectAll('roles')" class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</button></label>
                <select name="roles[]" id="roles" class="form-control select2" multiple="multiple" required>
                    @foreach($roles as $id => $roles)
                    <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                <em class="invalid-feedback">
                    {{ $errors->first('roles') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p>
            </div>
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ $user->is_active === 1 ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Active</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
                <div class="col-md-2">
                    <p id="loading"></p>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function selectAll(selectEl) {
        let options = $.map(
            $(`select#${selectEl} option`).filter((i, el) => {
                return $(el).attr('value') != "";
            }),
            option => {
                return $(option).attr('value');
            }
        );
        $(`#${selectEl}`).val(options);
        $(`#${selectEl}`).trigger('change');
    }

    function diselectAll(selectEl) {
        $(`#${selectEl}`).val([]);
        $(`#${selectEl}`).trigger('change');
    }

    $(document).ready(() => {
        $('#projects').select2({
            placeholder: 'Choose one or more...'
        });

        $('#roles').select2({
            placeholder: 'Choose one or more...'
        });
    });
</script>
@endsection