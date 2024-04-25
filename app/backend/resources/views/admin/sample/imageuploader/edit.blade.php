@extends('admin.base')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Test Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful test admin panel.</p>

    <p>{{$subTitle}}</p>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <x-adminlte-input name="iName" label="name" placeholder="name" fgroup-class="col-md-6" disable-feedback/>
                <x-adminlte-input-file name="ifMin" label="upload file" fgroup-class="col-md-6"/>
                <x-adminlte-select2 name="sel2Basic" label="testSelect" fgroup-class="col-md-6">
                    <option>Option 1</option>
                    <option disabled>Option 2</option>
                    <option selected>Option 3</option>
                </x-adminlte-select2>
            </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <x-adminlte-button label="Danger" theme="danger" icon="fas fa-ban"/>
                <x-adminlte-button label="Back" theme="secondary" icon="fas fa-thumbs-down" onclick="location.href='{{route('admin.sampleImageUploader1')}}'"/>
                <x-adminlte-button label="Back" theme="secondary" icon="fas fa-arrow-left"  onclick="location.href='{{route('admin.sampleImageUploader1')}}'"/>
                <x-adminlte-button label="Success" theme="success" icon="fas fa-thumbs-up"/>
            </div>
        </div>
    </div>
@stop
