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
            </div>
        </div>
    </div>


    {{-- Email type --}}
    <x-adminlte-input name="iMail" type="email" placeholder="mail@example.com"/>

    {{-- With label, invalid feedback disabled, and form group class --}}
    <div class="row">
        <x-adminlte-input name="iLabel" label="Label" placeholder="placeholder"
            fgroup-class="col-md-6" disable-feedback/>
    </div>

    {{-- With prepend slot --}}
    <x-adminlte-input name="iUser" label="User" placeholder="username" label-class="text-lightblue">
        <x-slot name="prependSlot">
            <div class="input-group-text">
                <i class="fas fa-user text-lightblue"></i>
            </div>
        </x-slot>
    </x-adminlte-input>

    {{-- With append slot, number type, and sm size --}}
    <x-adminlte-input name="iNum" label="Number" placeholder="number" type="number" igroup-size="sm" min=1 max=10>
        <x-slot name="appendSlot">
            <div class="input-group-text bg-dark">
                <i class="fas fa-hashtag"></i>
            </div>
        </x-slot>
    </x-adminlte-input>

    {{-- With multiple slots, and lg size --}}
    <x-adminlte-input name="iSearch" label="Search" placeholder="search" igroup-size="lg">
        <x-slot name="appendSlot">
            <x-adminlte-button theme="outline-danger" label="Go!"/>
        </x-slot>
        <x-slot name="prependSlot">
            <div class="input-group-text text-danger">
                <i class="fas fa-search"></i>
            </div>
        </x-slot>
    </x-adminlte-input>


    {{-- Input File --}}

    {{-- Minimal --}}
    <x-adminlte-input-file name="ifMin"/>

    {{-- Placeholder, sm size, and prepend icon --}}
    <x-adminlte-input-file name="ifPholder" igroup-size="sm" placeholder="Choose a file...">
        <x-slot name="prependSlot">
            <div class="input-group-text bg-lightblue">
                <i class="fas fa-upload"></i>
            </div>
        </x-slot>
    </x-adminlte-input-file>

    {{-- With label and feedback disabled --}}
    <x-adminlte-input-file name="ifLabel" label="Upload file" placeholder="Choose a file..."
        disable-feedback/>

    {{-- With multiple slots and multiple files --}}
    <x-adminlte-input-file id="ifMultiple" name="ifMultiple[]" label="Upload files"
        placeholder="Choose multiple files..." igroup-size="lg" legend="Choose" multiple>
        <x-slot name="appendSlot">
            <x-adminlte-button theme="primary" label="Upload"/>
        </x-slot>
        <x-slot name="prependSlot">
            <div class="input-group-text text-primary">
                <i class="fas fa-file-upload"></i>
            </div>
        </x-slot>
    </x-adminlte-input-file>

    {{-- Example with empty option (for Select2) --}}
    <x-adminlte-select2 name="optionsVehicles" igroup-size="lg" label-class="text-lightblue"
        data-placeholder="Select an option...">
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-info">
                <i class="fas fa-car-side"></i>
            </div>
        </x-slot>
        <x-adminlte-options :options="['Car', 'Truck', 'Motorcycle']" empty-option/>
    </x-adminlte-select2>

    {{-- With prepend slot, lg size, and label --}}
    <x-adminlte-select name="selVehicle" label="Vehicle" label-class="text-lightblue"
        igroup-size="lg">
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-info">
                <i class="fas fa-car-side"></i>
            </div>
        </x-slot>
        <option>Vehicle 1</option>
        <option>Vehicle 2</option>
    </x-adminlte-select>


    {{-- Select2 --}}
    {{-- Minimal --}}
    <x-adminlte-select2 name="sel2Basic">
        <option>Option 1</option>
        <option disabled>Option 2</option>
        <option selected>Option 3</option>
    </x-adminlte-select2>

    {{-- Disabled --}}
    <x-adminlte-select2 name="sel2Disabled" disabled>
        <option>Option 1</option>
        <option>Option 2</option>
    </x-adminlte-select2>

    {{-- With prepend slot, label, and data-placeholder config --}}
    <x-adminlte-select2 name="sel2Vehicle" label="Vehicle" label-class="text-lightblue" igroup-size="lg" data-placeholder="Select an option... Select2">
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-info">
                <i class="fas fa-car-side"></i>
            </div>
        </x-slot>
        <option/>
        <option>Vehicle 1</option>
        <option>Vehicle 2</option>
    </x-adminlte-select2>

    {{-- With multiple slots, and plugin config parameters --}}
    @php
        $config = [
            "placeholder" => "Select multiple options...",
            "allowClear" => true,
        ];
    @endphp
    <x-adminlte-select2 id="sel2Category" name="sel2Category[]" label="Categories"
        label-class="text-danger" igroup-size="sm" :config="$config" multiple>
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-red">
                <i class="fas fa-tag"></i>
            </div>
        </x-slot>
        <x-slot name="appendSlot">
            <x-adminlte-button theme="outline-dark" label="Clear" icon="fas fa-lg fa-ban text-danger"/>
        </x-slot>
        <option>Sports</option>
        <option>News</option>
        <option>Games</option>
        <option>Science</option>
        <option>Maths</option>
    </x-adminlte-select2>

    {{-- TextArea --}}
    {{-- Minimal with placeholder --}}
    <x-adminlte-textarea name="taBasic" placeholder="Insert description..."/>

    {{-- Disabled --}}
    <x-adminlte-textarea name="taDisabled" disabled>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam quis nibh massa.
    </x-adminlte-textarea>

    {{-- With prepend slot, sm size, and label --}}
    <x-adminlte-textarea name="taDesc" label="Description" rows=5 label-class="text-warning"
        igroup-size="sm" placeholder="Insert description...">
        <x-slot name="prependSlot">
            <div class="input-group-text bg-dark">
                <i class="fas fa-lg fa-file-alt text-warning"></i>
            </div>
        </x-slot>
    </x-adminlte-textarea>

    {{-- With slots, sm size, and feedback disabled --}}
    <x-adminlte-textarea name="taMsg" label="Message" rows=5 igroup-size="sm"
        label-class="text-primary" placeholder="Write your message..." disable-feedback>
        <x-slot name="prependSlot">
            <div class="input-group-text">
                <i class="fas fa-lg fa-comment-dots text-primary"></i>
            </div>
        </x-slot>
        <x-slot name="appendSlot">
            <x-adminlte-button theme="primary" icon="fas fa-paper-plane" label="Send"/>
        </x-slot>
    </x-adminlte-textarea>




    {{-- Alert --}}

    {{-- Minimal with title and dismissable --}}
    <x-adminlte-alert title="Well done!" dismissable>
        Minimal example
    </x-adminlte-alert>

    {{-- Minimal with icon only --}}
    <x-adminlte-alert icon="fas fa-user">
        User has logged in!
    </x-adminlte-alert>

    {{-- Themes --}}
    <x-adminlte-alert theme="light" title="Tip">
        Light theme alert!
    </x-adminlte-alert>
    <x-adminlte-alert theme="dark" title="Important">
        Dark theme alert!
    </x-adminlte-alert>

    {{-- Custom --}}
    <x-adminlte-callout theme="success" class="bg-teal" icon="fas fa-lg fa-thumbs-up" title="Done">
        <i class="text-dark">Your payment was complete!</i>
    </x-adminlte-callout>
    <x-adminlte-callout theme="danger" title-class="text-danger text-uppercase"
        icon="fas fa-lg fa-exclamation-circle" title="Payment Error">
        <i>There was an error on the payment procedure!</i>
    </x-adminlte-callout>
    <x-adminlte-callout theme="info" class="bg-gradient-info" title-class="text-bold text-dark"
        icon="fas fa-lg fa-bell text-dark" title="Notification">
        This is a notification.
    </x-adminlte-callout>


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

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <x-sample.sample-input type="text" name="test" value="TestValue" disabled="false"/>
            </div>
        </div>
    </div>
@stop
