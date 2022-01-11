@extends('layouts.admin')

@section('styles')
    <style>
        .photo__options {
            text-align: center !important;
            margin-top: 0 !important;
        }
    </style>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form id="formProfile" action="{{ route("admin.profile.store") }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Profile Picture --}}
            <div class="d-flex justify-content-center">
                <div class="profile">
                    <div class="photo">
                        <input type="file" accept="image/*">
                        <div class="photo__helper">
                            <div class="photo__frame photo__frame--circle">
                                <canvas class="photo__canvas"></canvas>
                                <div class="message is-empty">
                                    <p class="message--desktop">Drop your photo here or browse your computer.</p>
                                    <p class="message--mobile">Tap here to select your picture.</p>
                                </div>
                                <div class="message is-loading">
                                    <i class="fa fa-2x fa-spin fa-spinner"></i>
                                </div>
                                <div class="message is-dragover">
                                    <i class="fa fa-2x fa-cloud-upload"></i>
                                    <p>Drop your photo</p>
                                </div>
                                <div class="message is-wrong-file-type">
                                    <p>Only images allowed.</p>
                                    <p class="message--desktop">Drop your photo here or browse your computer.</p>
                                    <p class="message--mobile">Tap here to select your picture.</p>
                                </div>
                                <div class="message is-wrong-image-size">
                                    <p>Your photo must be larger than 350px.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="photo__options hide">
                                    <div class="photo__zoom">
                                        <input type="range" class="zoom-handler">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:;" class="remove"><i class="text-danger fa fa-trash mr-2"></i></a>
                        <a href="javascript:;" class="setDefault"><i class="fas fa-sync-alt"></i></a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="photo" id="photo" value="">

            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
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
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
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
                <input type="text" id="company" name="company" class="form-control" value="{{ old('name', auth()->user()->company ?? '') }}">
                @if($errors->has('company'))
                    <em class="invalid-feedback">
                        {{ $errors->first('company') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.company_helper') }}
                </p>
            </div>

            {{-- <hr class="my-5">

            <h4>Change Password</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="password">Old {{ trans('cruds.user.fields.password') }}</label>
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
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('new_password') ? 'has-error' : '' }}">
                        <label for="new_password">New {{ trans('cruds.user.fields.password') }}</label>
                        <input type="password" id="new_password" name="new_password" class="form-control">
                        @if($errors->has('new_password'))
                            <em class="invalid-feedback">
                                {{ $errors->first('new_password') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.user.fields.password_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="password">Confirm {{ trans('cruds.user.fields.password') }}</label>
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
                </div>
            </div> --}}

            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        let p = null;
        let hasPhoto = true;
        function initProfilePic() {
            p = new profilePicture('.profile', "{{ empty(auth()->user()->photo) ? Avatar::create(auth()->user()->name)->toBase64() : FunctionHelper::getImageFromMediaLibrary(auth()->user()->photo, 350, 350, true) }}", {
                imageHelper: true,
                onLoad: function (type) {
                    hasPhoto = true;
                },
                onRemove: function (type) {
                    $('.preview').hide().attr('src', '');
                    hasPhoto = false;
                },
                onError: function (type) {
                    console.log('Error type: ' + type);
                }
            });
        }

        function iniEvents() {
            $('#formProfile').on('submit', function (e) {
                if (hasPhoto) {
                    $('#photo').val(p.getAsDataURL());
                    return true;
                }
                else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Profile pic is required'
                    });
                    return false;
                }
            });

            $('.setDefault').on('click', function (e) {
                p = new profilePicture('.profile', "{{ Avatar::create(auth()->user()->name)->toBase64() }}", {
                    imageHelper: true,
                    onLoad: function (type) {
                        hasPhoto = true;
                    },
                    onRemove: function (type) {
                        $('.preview').hide().attr('src', '');
                        hasPhoto = false;
                    },
                    onError: function (type) {
                        console.log('Error type: ' + type);
                    }
                });
            });
        }

        $(document).ready(() => {
            initProfilePic();
            iniEvents();
        });
    </script>
@endsection
