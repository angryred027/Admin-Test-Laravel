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
        <div class="d-flex flex-column flex-grow-1">
            <div>
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
                >{{$value ?? ''}}</textarea>
            </div>
            <div class="d-flex justify-content-end text-secondary text-sm">
                <span id="{{$name . '_text_counter'}}">{{mb_strlen($value ?? '')}}</span>/{{$maxLength}}
            </div>
        </div>
    </div>
    @error($name)
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{$message}}</strong>
        </span>
    @enderror
</div>


@section('js')
    @parent
    <script>
        initTextAreaComponent(`{{$name}}`);

        /**
        * initialize
        * @param {string} name
        * @return {void}
        */
        function initTextAreaComponent(name) {
            const textaAreaId = name + '_text_area'
            const textCounterId = name + '_text_counter'
            const textArea = document.getElementById(textaAreaId);
            const textCounter = document.getElementById(textCounterId);

            if (textArea && textCounter) {
                textArea.addEventListener('keyup', function(event){
                    textCounter.textContent = textArea.value.length
                });
            }
        }
    </script>
@stop
