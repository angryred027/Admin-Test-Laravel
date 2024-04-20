@extends('admin.base')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Test Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful test admin panel.</p>

    <p>SampleComponents.</p>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <x-adminlte-input name="iMail" label="mail" type="email" placeholder="mail@example.com" fgroup-class="col-md-6" disable-feedback/>

                <x-adminlte-input name="iName" label="name" placeholder="name" fgroup-class="col-md-6" disable-feedback/>

                <x-adminlte-input name="iAddress" label="Address" placeholder="address" fgroup-class="col-md-6" disable-feedback/>
                <x-adminlte-input name="iPassword" label="Password" type="password" placeholder="password" fgroup-class="col-md-6" disable-feedback/>
                <x-adminlte-input-file name="ifMin" label="upload file" fgroup-class="col-md-6"/>

                <div class="form-group col-md-6">
                    <label for="idDate">inputDate</label>
                    <div class="input-group">
                        <input name="idDate" type="date" placeholder="input date" label="inputDate" class="form-control"/>
                        <input name="idTime" type="time" placeholder="HH:mm" label="instaTime" class="form-control"/>
                    </div>
                </div>

                <x-adminlte-input-date name="idBasic" placeholder="input date" label="inputDate" fgroup-class="col-md-6"/>
                <x-adminlte-select2 name="sel2Basic" label="testSelect" fgroup-class="col-md-6">
                    <option>Option 1</option>
                    <option disabled>Option 2</option>
                    <option selected>Option 3</option>
                </x-adminlte-select2>
                <x-adminlte-textarea name="taBasic" label="testTextArea" placeholder="Insert description..." fgroup-class="col-md-6"/>
            </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <x-adminlte-button label="Primary" theme="primary" icon="fas fa-key"/>
                <x-adminlte-button label="Secondary" theme="secondary" icon="fas fa-hashtag"/>
                <x-adminlte-button label="Info" theme="info" icon="fas fa-info-circle"/>
                <x-adminlte-button label="Warning" theme="warning" icon="fas fa-exclamation-triangle"/>
                <x-adminlte-button label="Danger" theme="danger" icon="fas fa-ban"/>
                <x-adminlte-button label="Success" theme="success" icon="fas fa-thumbs-up"/>
            </div>
        </div>
    </div>

    {{-- DataTable --}}
    {{-- Setup data for datatables --}}
    @php
    $heads = [
        'ID',
        'Name',
        ['label' => 'Phone', 'width' => 40],
        ['label' => 'Actions', 'no-export' => true, 'width' => 5],
    ];

    $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                    <i class="fa fa-lg fa-fw fa-pen"></i>
                </button>';
    $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                    <i class="fa fa-lg fa-fw fa-trash"></i>
                </button>';
    $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                    <i class="fa fa-lg fa-fw fa-eye"></i>
                </button>';

    $config = [
        'data' => [
            [22, 'John Bender', '+02 (123) 123456789', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
            [19, 'Sophia Clemens', '+99 (987) 987654321', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
            [3, 'Peter Sousa', '+69 (555) 12367345243', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
        ],
        'order' => [[1, 'asc']],
        'columns' => [null, null, null, ['orderable' => false]],
    ];
    @endphp

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <x-adminlte-datatable id="table1" :heads="$heads" hoverable bordered compressed>
                    @foreach($config['data'] as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{!! $cell !!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
            </div>
        </div>
    </div>
@stop
