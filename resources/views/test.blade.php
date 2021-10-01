@extends('layouts.admin')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

@endsection

{{-- TITLE --}}
@section('title', '')

{{-- CONTENT --}}
@section('content')

@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let x = 1;

        if (x == 1) {
            axios.defaults.withCredentials = true;
            axios.post('http://monstercode.ip-dynamic.com:8087/ticket-support/api/v1/login', {
                email: "ekkys99@gmail.com",
                password: "password"
            })
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
        } else {
            $.ajax({
                type: "POST",
                url: "http://monstercode.ip-dynamic.com:8087/ticket-support/api/v1/login",
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify({
                    email: "ekkys99@gmail.com",
                    password: "password"
                }),
                success: function(response) {
                    console.log(response);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

    </script>
@endsection
