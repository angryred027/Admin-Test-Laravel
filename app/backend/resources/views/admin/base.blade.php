@extends('adminlte::page')

@section('title', 'Base Dashboard')

@section('content_header')
    <h1>Base Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful base admin panel.</p>
@stop

@section('adminlte_css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    {{--  動的に設定するCSS  --}}
    <style>
        .brand-link-color {
            background-color: {{ config('myapp.mainColor') }};
        }
    </style>
@stop

@section('js')
    <script> console.log('Test!'); </script>
@stop
