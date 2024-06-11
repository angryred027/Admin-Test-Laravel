@props([
    'class' => '',
    'name' => '',
    'value' => null,
    'required' => false,
    'isDateOnly' => false,
    'isSetLimitTime' => false,
])
<div class="sample-date-input form-group col-md-6">
    <label for="$name">inputDate</label>
    <div class="input-group @error(($name || $name . '_time')) adminlte-invalid-igroup @enderror">
        <input
            id="{{$name . '_date'}}"
            name="{{$name}}"
            type="date"
            placeholder="input date"
            label="$name"
            {{ $required ? 'required' : '' }}
            value="$value"
            class="form-control @error($name) is-invalid @enderror"
        />
        @if (!$isDateOnly)
            <input
                id="{{$name . '_time'}}"
                name="{{$name . '_time'}}"
                type="time"
                placeholder="HH:mm"
                label={{$name . '_time'}}
                {{ $required ? 'required' : '' }}
                value="$startValue"
                step="1"
                class="form-control @error($name .'_time') is-invalid @enderror"
            />
        @endif
        @if ($isSetLimitTime)
            <x-adminlte-button id="{{$name . '_copy_btn'}}" label="copy" theme="secondary" icon="fas fa-clock" />
        @endif
    </div>
    @error(($name || $name . '_time'))
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{$message}}</strong>
        </span>
    @enderror
</div>

@section('js')
    @parent
    <script>
        if (!!`{{$isSetLimitTime}}`) {
            initDatetimeComponent(`{{$name}}`)
        }

        /**
        * initialize
        * @param {string} name
        * @return {void}
        */
        function initDatetimeComponent(name) {
            initCopyButtonComponent(name)
        }

        /**
        * initialize copy button
        * @param {string} name
        * @return {void}
        */
        function initCopyButtonComponent(name) {
            const copyButton = document.getElementById(`${name}_copy_btn`)

            copyButton.addEventListener('click', function(evt){
                const dateForm = document.getElementById(`${name}_date`)
                const timeForm = document.getElementById(`${name}_time`)

                if (dateForm && timeForm) {
                    dateForm.value = '2030-12-21'
                    timeForm.value = '23:59:59'
                    // イベント発行
                    dateForm.dispatchEvent(new Event('change'))
                    timeForm.dispatchEvent(new Event('change'))
                }
            });
        }

    </script>
@stop
