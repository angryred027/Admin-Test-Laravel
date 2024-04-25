{{-- @extends('adminlte::page') --}}
@extends('admin.base')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Test Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful test admin panel.</p>


    <x-adminlte-button label="Button"/>
    <x-adminlte-button label="Disabled" theme="dark" disabled/>
@stop
