@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
        </div>

        <div class="card-body">
            <form action="{{ route("admin.users.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
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

                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <input type="password" id="password" name="password" class="form-control" required>
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
                    <label for="roles">{{ trans('cruds.user.fields.roles') }}*</label>
                    <select name="roles[]" id="roles" class="form-control select2" required>
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

                <div class="form-group {{ $errors->has('project') ? 'has-error' : '' }}">
                    <div id="project-container"></div>
                    @if($errors->has('project'))
                        <em class="invalid-feedback">
                            {{ $errors->first('project') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.project_helper') }}
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
        $(document).ready(() => {
            $('#roles').select2({
                placeholder: "Choose one.."
            });
        });

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

        $('#roles').on('select2:select', function (e) {
            var id = $(this).attr('id');
                // var val = $(this).val();
            var val = e.params.data.id;
            let input_project = "";

            if(val != 1){
                if (val == 2){
                    input_project += '<label for="project">Proyek* ';
                    input_project += '<button type="button" onclick="selectAll(\'projects\')" class="btn btn-info btn-xs select-all mx-2">Select All</button>';
                    input_project += '<button type="button" onclick="diselectAll(\'projects\')" class="btn btn-info btn-xs deselect-all">Deselect All</button></label>';
                    input_project += '<select name="projects[]" id="projects" class="form-control select2" multiple="multiple" >';
                }
                else {
                    input_project += '<label for="project">Proyek*</label>';
                    input_project += '<select name="projects[]" id="projects" class="form-control select2"><option></option>';
                }

                input_project += '@foreach($projects as $project)';
                input_project += '<option value="{{ $project->id }}">{{ $project->name }}</option>';
                input_project += '@endforeach';
                input_project += '</select>';

                $("#project-container").html(input_project);
                $('#projects').select2({
                    placeholder: `Choose one${val == 2 ? ' or more...' : '...'}`
                });
            }
        });
    </script>
@endsection
