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
        @foreach($optionList as $value => $label)
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

@section('js')
    @parent
    <script>
        if (!!`{{$required}}`) {
            initCheckBoxComponent(`{{$name}}`, JSON.parse(`{{json_encode(array_keys($optionList))}}`))
            // initCheckBoxComponent(`{{$name}}`, {{json_encode(array_keys($optionList))}})
        }

        /**
        * initialize
        * @param {string} name
        * @param {string[]} valueList
        * @return {void}
        */
        function initCheckBoxComponent(name, valueList) {
            const checkboxList = []
            for (const v of valueList) {
                checkboxList.push(document.getElementById(`${name}_checkbox_${v}`))
            }


            for (const checkbox of checkboxList) {
                checkbox.addEventListener('change', function(event){
                    validateCheckbox(name, valueList)
                });
            }
        }

        /**
        * initialize
        * @param {string} name
        * @param {number[]} valueList
        * @return {void}
        */
        function validateCheckbox(name, valueList) {
            const checkboxList = []
            for(const v of valueList) {
                checkboxList.push(document.getElementById(`${name}_checkbox_${v}`))
            }

            for(const checkbox of checkboxList) {
                checkbox.setAttribute('required', 'true')
            }

            let isValid = false
            for(const checkbox of checkboxList) {
                isValid = checkbox.checked
                if (isValid) {
                    break
                }
            }

            if (isValid) {
                for(const checkbox of checkboxList) {
                    checkbox.removeAttribute('required')
                }
            }
        }
    </script>
@stop
