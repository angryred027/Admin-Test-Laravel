@extends('adminlte::page')

@section('title', 'Base Dashboard')

@section('content_header')
    {{--  {{ Breadcrumbs::render('admin.home') }}  --}}
    {{--  {{ Breadcrumbs::render('admin.sampleImageUploader1') }}  --}}
    {{--  {{ Breadcrumbs::render(request()->route()->getName()) }}  --}}

    {{ Breadcrumbs::getResource(request()->route()->getName(), request()->route()->originalParameters()) }}
    {{ Breadcrumbs::render() }}
    {{--  <h1>Base Dashboard</h1>  --}}
@stop

@section('content')
    <p>Welcome to this beautiful base admin panel.</p>
@stop

@section('adminlte_css')
    @parent
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
