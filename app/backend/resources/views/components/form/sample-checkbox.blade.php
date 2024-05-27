@props([
    'class' => '',
    'name' => '',
    'valueList' => [],
    'optionList' => [],
    'required' => false,
    'disabled' => false,
])
<div class="form-group col-md-6">
    <div class="d-flex flex-column">
        @foreach ($optionList as $value => $label)
            <div class="icheck-danger">
                <input
                    type="checkbox"
                    id="{{$name . "_checkbox_$value"}}"
                    name="{{$name . '[]'}}"
                    value="{{$value}}"
                    {{in_array($value, $valueList, true) ? 'checked' : ''}}
                    {{$required ? 'required' : ''}}
                    {{$disabled ? 'disabled' : ''}}
                >
                <label for="{{$name . "_checkbox_$value"}}" class="mb-0">{{$label}}</label>
            </div>
        @endforeach
    </div>
</div>

