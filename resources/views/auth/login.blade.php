@extends('layouts.auth')
@section('content')
<div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="container mb-3">
                                <img src="{{ asset('theme/img/logo-group-1.png') }}" width="100%" height="auto">
                                <h3 class="text-center mb-2">{{ trans('panel.site_title') }}</h3>
                                {{-- <p class="text-muted">{{ trans('global.login') }}</p> --}}
                            </div>

                            @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif

                            <form class="user" method="POST" action="{{ route('login') }}">
                            @csrf
                                <div class="form-group">
                                    <input class="form-control form-control-user"
                                    id="email" name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">

                                    @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                    @endif
                                </div>


                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user"
                                    id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}">

                                    @if($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">

                                        <input class="form-check-input" name="remember" type="checkbox" id="remember" style="vertical-align: middle;"  class="custom-control-input"/>

                                        <label class="custom-control-label" for="remember" style="vertical-align: middle;">
                                            {{ trans('global.remember_me') }}
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block"">
                                    {{ trans('global.login') }}
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                @if(Route::has('password.request'))
                                <a class="small" href="{{ route('password.request') }}">
                                    {{ trans('global.forgot_password') }}
                                </a><br>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img src="{{ asset('theme/img/note.png') }}" style="width: auto; height:  600px; position: center;" >
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection