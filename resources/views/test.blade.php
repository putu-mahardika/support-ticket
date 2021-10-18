@php


@endphp
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
    @version
@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function toSnakeCase(str) {
            return str &&
            str
                .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
                .map(x => x.toLowerCase())
                .join('_');
        }
    </script>
@endsection
