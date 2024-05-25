@props([
    'class' => '',
    'name' => '',
    'lable' => 'テストラベル',
    'placeholder' => 'テキストエリア',
    'value' => null,
    'required' => false,
    'disabled' => false,
    'maxLength' => 1000,
    'cols' => 20,
    'rows' => 2,
])
<div class="sample-date-input form-group col-md-6">
    <label for="$name">{{$lable}}</label>
    <div class="input-group @error($name) adminlte-invalid-igroup @enderror">
        <textarea
            id="{{$name . '_text_area'}}"
            name="{{$name}}"
            placeholder="{{$placeholder}}"
            label="{{$name}}"
            maxlength="{{$maxLength}}"
            cols="{{$cols}}"
            rows="{{$rows}}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="form-control @error($name) is-invalid @enderror"
        >{{$value}}</textarea>
    </div>
    @error($name)
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{$message}}</strong>
        </span>
    @enderror
</div>
