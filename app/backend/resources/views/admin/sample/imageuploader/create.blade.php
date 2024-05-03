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
                <form method="POST" enctype="multipart/form-data" action={{route('admin.sampleImageUploader1.post')}}>
                    @csrf
                    <x-adminlte-input name="name" label="name" placeholder="name" fgroup-class="col-md-6" value={{$name}}/>
                    <x-form.sample-select name="testSelet1" value="" label="testSelet1" placeholder="placeholder" :options="[1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3']"/>
                    {{--  <x-adminlte-select name="testSelet1" label="testSelect1" fgroup-class="col-md-6">
                        <x-adminlte-options :options="[1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3']" disabled="1"
                            empty-option="Select an option..."/>
                    </x-adminlte-select>  --}}
                    <x-form.sample-input-date name="statr_time" value="" startValue=""/>

                    {{--  <div class="form-group col-md-6">
                        <label for="testDate">inputDate</label>
                        <div class="input-group @error('testDate') adminlte-invalid-igroup @enderror  @error('testTime') adminlte-invalid-igroup @enderror">
                            <input name="testDate" type="date" placeholder="input date" label="testDate" required="true" class="form-control @error('testDate') is-invalid @enderror"/>
                            <input name="testTime" type="time" placeholder="HH:mm" label="testTime" required="true" class="form-control @error('testTime') is-invalid @enderror"/>
                        </div>
                        @error('testDate')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                        @enderror
                        @error('testTime')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                        @enderror
                    </div>  --}}
                    <x-adminlte-input-file name="file" label="upload file" fgroup-class="col-md-6" value={{$image}}/>
                    {{--  <x-adminlte-select2 name="testSelet2" label="testSelect2" fgroup-class="col-md-6">
                        <option>Option 1</option>
                        <option disabled>Option 2</option>
                        <option selected>Option 3</option>
                    </x-adminlte-select2>  --}}
                </form>
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
