@extends('admin.base')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Test Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful test admin panel.</p>


    <p>SampleComponents.</p>
    <x-adminlte-button label="Button"/>
    <x-adminlte-button label="Disabled" theme="dark" disabled/>

    {{-- Button with themes and icons --}}
    <x-adminlte-button label="Primary" theme="primary" icon="fas fa-key"/>
    <x-adminlte-button label="Secondary" theme="secondary" icon="fas fa-hashtag"/>
    <x-adminlte-button label="Info" theme="info" icon="fas fa-info-circle"/>
    <x-adminlte-button label="Warning" theme="warning" icon="fas fa-exclamation-triangle"/>
    <x-adminlte-button label="Danger" theme="danger" icon="fas fa-ban"/>
    <x-adminlte-button label="Success" theme="success" icon="fas fa-thumbs-up"/>
    <x-adminlte-button label="Dark" theme="dark" icon="fas fa-adjust"/>

    {{-- Button with types --}}
    <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
    <x-adminlte-button class="btn-lg" type="reset" label="Reset" theme="outline-danger" icon="fas fa-lg fa-trash"/>
    <x-adminlte-button class="btn-sm bg-gradient-info" type="button" label="Help" icon="fas fa-lg fa-question"/>

    {{-- Icons only buttons --}}
    <x-adminlte-button theme="primary" icon="fab fa-fw fa-lg fa-facebook-f"/>
    <x-adminlte-button theme="info" icon="fab fa-fw fa-lg fa-twitter"/>
@stop
