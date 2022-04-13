@extends('layouts.auth')
@section('styles')
    <style>
        #motto {
            position: absolute;
            top: 5rem;
            background-color: rgba(78, 115, 223, 0.2);
            color: var(--blue);
            font-weight: 700;
            padding: 1rem 1.5rem 1rem 1.5rem;
            border-radius: 1.5rem 1.5rem 0 1.5rem;
        }
    </style>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="pt-5 pl-5 pr-5 pb-0">
                                <div class="text-center mb-5">
                                    <img src="{{ asset('theme/img/logo-group-1.png') }}" style="width: 15rem; height: auto;">
                                    <h3 class="text-center mb-2">
                                        {{ config('app.name') }}
                                    </h3>
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
                                            id="email" name="email"
                                            type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            required autocomplete="email"
                                            autofocus placeholder="{{ trans('global.login_email') }}"
                                            value="{{ old('email', null) }}">

                                        @if($errors->has('email'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('email') }}
                                            </div>
                                        @endif
                                    </div>


                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="password" class="form-control form-control-user" style="border-radius:10rem;" id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}">
                                            <span class="input-group-text" onclick="password_show_hide();" style="cursor: pointer; background-color:transparent;border:none;margin-left:-45px;z-index:10;">
                                                <i class="fas fa-eye-slash" id="btn-eye"></i>
                                            </span>
                                        </div>
                                        @if($errors->has('password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        <i class="fas fa-sign-in-alt"></i> {{ trans('global.login') }}
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
                                <p class="small mt-5 text-center">@version('compact')</p>
                            </div>
                        </div>
                        <div class="col-lg-6 d-none d-lg-block position-relative">
                            <h6 id="motto">
                                <em>Reliable Helpdesk<br>Application...</em>
                            </h6>
                            <img src="{{ asset('theme/img/ill-1.png') }}" style="margin-top: 5rem; width: auto; height: 20rem; position: right;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <p>version 1.0.1</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function password_show_hide() {
        var x = document.getElementById("password");
        var show_eye = document.getElementById("btn-eye");
        if (x.type === "password") {
            x.type = "text";
            show_eye.classList.remove("fa-eye-slash");
            show_eye.classList.add("fa-eye");
        } else {
            x.type = "password";
            show_eye.classList.add("fa-eye-slash");
            show_eye.classList.remove("fa-eye");
        }
    }
</script>
