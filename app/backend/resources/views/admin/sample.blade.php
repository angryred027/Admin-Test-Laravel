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




    {{-- Alert --}}
    {{-- Minimal --}}
    <x-adminlte-alert>Minimal example</x-adminlte-alert>

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
    <x-adminlte-alert theme="primary" title="Primary Notification">
        Primary theme alert!
    </x-adminlte-alert>
    <x-adminlte-alert theme="secondary" icon="" title="Secondary Notification">
        Secondary theme alert!
    </x-adminlte-alert>
    <x-adminlte-alert theme="info" title="Info">
        Info theme alert!
    </x-adminlte-alert>
    <x-adminlte-alert theme="success" title="Success">
        Success theme alert!
    </x-adminlte-alert>
    <x-adminlte-alert theme="warning" title="Warning">
        Warning theme alert!
    </x-adminlte-alert>
    <x-adminlte-alert theme="danger" title="Danger">
        Danger theme alert!
    </x-adminlte-alert>

    {{-- Custom --}}
    <x-adminlte-alert class="bg-teal text-uppercase" icon="fa fa-lg fa-thumbs-up" title="Done" dismissable>
        Your payment was complete!
    </x-adminlte-alert>

    {{-- Minimal --}}
    <x-adminlte-callout>Minimal example</x-adminlte-callout>

    {{-- themes --}}
    <x-adminlte-callout theme="info" title="Information">
        Info theme callout!
    </x-adminlte-callout>
    <x-adminlte-callout theme="success" title="Success">
        Success theme callout!
    </x-adminlte-callout>
    <x-adminlte-callout theme="warning" title="Warning">
        Warning theme callout!
    </x-adminlte-callout>
    <x-adminlte-callout theme="danger" title="Danger">
        Danger theme callout!
    </x-adminlte-callout>

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
    <x-adminlte-callout theme="danger" class="bg-gradient-pink" title-class="text-uppercase"
        icon="fas fa-lg fa-leaf text-purple" title="observation">
        <i>A styled observation for the user.</i>
    </x-adminlte-callout>



    {{-- progress --}}
    {{-- Minimal --}}
    <x-adminlte-progress/>

    {{-- themes --}}
    <x-adminlte-progress theme="light" value=55/>
    <x-adminlte-progress theme="dark" value=30/>
    <x-adminlte-progress theme="primary" value=95/>
    <x-adminlte-progress theme="secondary" value=40/>
    <x-adminlte-progress theme="info" value=85/>
    <x-adminlte-progress theme="warning" value=25/>
    <x-adminlte-progress theme="danger" value=50/>
    <x-adminlte-progress theme="success" value=75/>

    {{-- Custom --}}
    <x-adminlte-progress theme="teal" value=75 animated/>
    <x-adminlte-progress size="sm" theme="indigo" value=85 animated/>
    <x-adminlte-progress theme="pink" value=50 animated with-label/>

    {{-- Vertical --}}
    <x-adminlte-progress theme="purple" value=40 vertical/>
    <x-adminlte-progress theme="orange" value=80 vertical animated/>
    <x-adminlte-progress theme="navy" value=70 vertical striped with-label/>
    <x-adminlte-progress theme="lime" size="xxs" value=90 vertical/>

    {{-- Dinamic Change --}}
    <x-adminlte-progress id="pbDinamic" value="5" theme="lighblue" animated with-label/>
    {{-- Update the previous progress bar every 2 seconds, incrementing by 10% each step --}}
    @push('js')
    <script>
        $(document).ready(function() {

            let pBar = new _AdminLTE_Progress('pbDinamic');

            let inc = (val) => {
                let v = pBar.getValue() + val;
                return v > 100 ? 0 : v;
            };

            setInterval(() => pBar.setValue(inc(10)), 2000);
        })
    </script>
    @endpush


    <div class="container">
        <div class="row">
            <div class="col-sm">
            One of three columns
            </div>
            <div class="col-sm">
            One of three columns
            </div>
            <div class="col-sm">
            One of three columns
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Email address</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Example select</label>
                        <select class="form-control" id="exampleFormControlSelect1">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Example multiple select</label>
                        <select multiple class="form-control" id="exampleFormControlSelect2">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Example textarea</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                </form>
                <form>
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Example file input</label>
                        <input type="file" class="form-control-file" id="exampleFormControlFile1">

                        <label for="exampleDatetime">Example file input</label>
                        <input type="date" class="form-control-date" id="exampleDatetime">
                        <input type="time" class="form-control-time" id="exampleTime">
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <x-sample.sample-input/>
            </div>
        </div>
    </div>
@stop
