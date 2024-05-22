@props([
    'class' => '',
    'name' => '',
    'value' => null,
    'required' => false,
    'isDateOnly' => false,
])
<div class="sample-date-input form-group col-md-6">
    <label for="$name">inputDate</label>
    <div class="input-group @error(($name || $name . '_time')) adminlte-invalid-igroup @enderror">
        <input
            name="$name"
            type="date"
            placeholder="input date"
            label="$name"
            {{ $required ? 'required' : '' }}
            value="$value"
            class="form-control @error($name) is-invalid @enderror"
        />
        @if (!$isDateOnly)
            <input
                name={{$name . '_time'}}
                type="time"
                placeholder="HH:mm"
                label={{$name . '_time'}}
                {{ $required ? 'required' : '' }}
                value="$startValue"
                step="1"
                class="form-control @error($name .'_time') is-invalid @enderror"
            />
        @endif
    </div>
    @error(($name || $name . '_time'))
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{$message}}</strong>
        </span>
    @enderror
</div>
