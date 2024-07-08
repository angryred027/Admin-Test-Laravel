@props([
    'class' => '',
    'name' => '',
    'value' => null,
    'optionList' => [],
    'required' => false,
    'disabled' => false,
])
<div class="form-group clearfix col-md-6">
    <div class="d-flex flex-column">
        @foreach($optionList as $optionLabel => $optionValue)
            <div class="custom-radio">
                <input
                    type="radio"
                    id="{{$name . "_radio_$optionValue"}}"
                    name={{$name}}
                    {{($optionValue === $value) ? 'checked' : ''}}
                    value="{{$optionValue}}"
                    {{$disabled ? 'disabled' : ''}}
                    {{$required ? 'required' : ''}}
                    class="custom-control-input custom-control-input-primary custom-control-input-outline"
                />
                <label for="{{$name . "_radio_$optionValue"}}" class="custom-control-label font-weight-normal">{{$optionLabel}}</label>
            </div>
        @endforeach
    </div>
</div>
