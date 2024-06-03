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
        @foreach($optionList as $optionValue => $label)
            <div class="custom-checkbox">
                <input
                    type="checkbox"
                    id="{{$name . "_checkbox_$optionValue"}}"
                    name="{{$name . '[]'}}"
                    value="{{$optionValue}}"
                    {{in_array($optionValue, $valueList, true) ? 'checked' : ''}}
                    {{$disabled ? 'disabled' : ''}}
                    class="custom-control-input custom-control-input-primary custom-control-input-outline"
                    {{($required && empty($valueList)) ? 'required' : ''}}
                />
                <label for="{{$name . "_checkbox_$optionValue"}}" class="custom-control-label font-weight-normal">{{$label}}</label>
            </div>
        @endforeach
    </div>
</div>

@section('js')
    @parent
    <script>
        if (!!`{{$required}}`) {
            // initCheckBoxComponent(`{{$name}}`, JSON.parse(`{{json_encode(array_keys($optionList))}}`))
            initCheckBoxComponent(`{{$name}}`, {{json_encode(array_keys($optionList))}})
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
        * @return {boolean}
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

            return isValid
        }
    </script>
@stop
