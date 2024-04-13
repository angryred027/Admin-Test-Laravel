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


    {{-- Input --}}

    {{-- Minimal --}}
    <x-adminlte-input name="iBasic"/>

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
    <x-adminlte-input name="iNum" label="Number" placeholder="number" type="number"
        igroup-size="sm" min=1 max=10>
        <x-slot name="appendSlot">
            <div class="input-group-text bg-dark">
                <i class="fas fa-hashtag"></i>
            </div>
        </x-slot>
    </x-adminlte-input>

    {{-- With a link on the bottom slot, and old support enabled --}}
    <x-adminlte-input name="iPostalCode" label="Postal Code" placeholder="postal code"
        enable-old-support>
        <x-slot name="prependSlot">
            <div class="input-group-text text-olive">
                <i class="fas fa-map-marked-alt"></i>
            </div>
        </x-slot>
        <x-slot name="bottomSlot">
            <a href="#">Search your postal code here</a>
        </x-slot>
    </x-adminlte-input>

    {{-- With extra information on the bottom slot --}}
    <x-adminlte-input name="iExtraAddress" label="Other Address Data">
        <x-slot name="prependSlot">
            <div class="input-group-text text-purple">
                <i class="fas fa-address-card"></i>
            </div>
        </x-slot>
        <x-slot name="bottomSlot">
            <span class="text-sm text-gray">
                [Add other address information you may consider important]
            </span>
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


    {{-- Options --}}

    {{-- Options with empty option --}}
    <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']"
            disabled="1" empty-option="Select an option..."/>

    {{-- Options with placeholder --}}
    <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']"
            disabled="1" placeholder="Select an option..."/>

    {{-- Example with empty option (for Select) --}}
    <x-adminlte-select name="optionsTest1">
        <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']" disabled="1"
            empty-option="Select an option..."/>
    </x-adminlte-select>

    {{-- Example with placeholder (for Select) --}}
    <x-adminlte-select name="optionsTest2">
        <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']" disabled="1"
            placeholder="Select an option..."/>
    </x-adminlte-select>

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

    {{-- Example with multiple selections (for Select) --}}
    @php
        $options = ['s' => 'Spanish', 'e' => 'English', 'p' => 'Portuguese'];
        $selected = ['s','e'];
    @endphp
    <x-adminlte-select id="optionsLangs" name="optionsLangs[]" label="Languages"
        label-class="text-danger" multiple>
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-red">
                <i class="fas fa-lg fa-language"></i>
            </div>
        </x-slot>
        <x-adminlte-options :options="$options" :selected="$selected"/>
    </x-adminlte-select>

    {{-- Example with multiple selections (for SelectBs) --}}
    @php
        $config = [
            "title" => "Select multiple options...",
            "liveSearch" => true,
            "liveSearchPlaceholder" => "Search...",
            "showTick" => true,
            "actionsBox" => true,
        ];
    @endphp
    <x-adminlte-select-bs id="optionsCategory" name="optionsCategory[]" label="Categories"
        label-class="text-danger" :config="$config" multiple>
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-red">
                <i class="fas fa-tag"></i>
            </div>
        </x-slot>
        <x-adminlte-options :options="['News', 'Sports', 'Science', 'Games']"/>
    </x-adminlte-select-bs>

    {{-- Select1 --}}
    {{-- Minimal --}}
    <x-adminlte-select name="selBasic">
        <option>Option 1</option>
        <option disabled>Option 2</option>
        <option selected>Option 3</option>
    </x-adminlte-select>

    {{-- Disabled --}}
    <x-adminlte-select name="selDisabled" disabled>
        <option>Option 1</option>
        <option>Option 2</option>
    </x-adminlte-select>

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

    {{-- With multiple slots and multiple options --}}
    <x-adminlte-select id="selUser" name="selUser[]" label="User" label-class="text-danger" multiple>
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-red">
                <i class="fas fa-lg fa-user"></i>
            </div>
        </x-slot>
        <x-slot name="appendSlot">
            <x-adminlte-button theme="outline-dark" label="Clear" icon="fas fa-lg fa-ban text-danger"/>
        </x-slot>
        <option>Admin</option>
        <option>Guest</option>
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
    <x-adminlte-select2 name="sel2Vehicle" label="Vehicle" label-class="text-lightblue"
        igroup-size="lg" data-placeholder="Select an option...">
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

@stop
