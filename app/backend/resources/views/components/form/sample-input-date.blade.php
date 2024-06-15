@props([
    'class' => '',
    'name' => '',
    'value' => null,
    'startTimeValue' => null,
    'required' => false,
    'isDateOnly' => false,
    'targetNumber' => null,
    'targetName' => null,
    'isSetLimitTime' => false,
])
<div class="sample-date-input form-group col-md-6">
    <label for="{{$name}}">inputDate</label>
    <div class="input-group @error(($name || $name . '_time')) adminlte-invalid-igroup @enderror">
        <input
            id="{{$name . '_date'}}"
            name="{{$name}}"
            type="date"
            placeholder="input date"
            label="{{$name}}"
            {{ $required ? 'required' : '' }}
            value="{{$value}}"
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
                value="{{$startTimeValue ?? '00:00:00'}}"
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
        initDatetimeComponent(
            `{{$name}}`,
            !!`{{$isDateOnly}}`,
            @if (is_null($targetNumber)) null @else {{$targetNumber}} @endif,
            @if (is_null($targetName)) null @else `{{$targetName}}` @endif,
            !!`{{$isSetLimitTime}}`
        )

        /**
        * initialize
        * @param {string} name
        * @param {boolean} isDateOnly
        * @param {null|number} targetNumber
        * @param {null|string} targetName
        * @param {boolean} isSetLimitTime
        * @return {void}
        */
        function initDatetimeComponent(name, isDateOnly, targetNumber, targetName, isSetLimitTime) {
            const dateInput = document.getElementById(`${name}_date`)
            const timeInput = document.getElementById(`${name}_time`)

            if (!isDateOnly) {
                if (targetNumber !== null || targetName) {
                    dateInput.addEventListener('change', function(event){
                        initValidateDatetime(dateInput, timeInput, targetNumber, targetName)
                    })
                    timeInput.addEventListener('change', function(event){
                        initValidateDatetime(dateInput, timeInput, targetNumber, targetName)
                    })

                    // 比較先も検証
                    const targetDateInput = document.getElementById(`${targetName}_date`)
                    const targetTimeInput = document.getElementById(`${targetName}_time`)
                    if (targetDateInput && targetTimeInput) {
                        targetDateInput.addEventListener('change', function(event){
                            initValidateDatetime(dateInput, timeInput, targetNumber, targetName)
                        })
                        targetTimeInput.addEventListener('change', function(event){
                            initValidateDatetime(dateInput, timeInput, targetNumber, targetName)
                        })
                    }
                }
            }
            if (isSetLimitTime) {
                initCopyButtonComponent(name)
            }
        }

        /**
        * initialize copy button
        * @param {string} name
        * @return {void}
        */
        function initCopyButtonComponent(name) {
            const copyButton = document.getElementById(`${name}_copy_btn`)

            copyButton.addEventListener('click', function(event){
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

        /**
        * validate datetime number
        * @param {HTMLElement} datetime
        * @param {HTMLElement} timeInput
        * @param {null|number} targetNumber
        * @param {null|string} targetName
        * @return {boolean}
        */
        function initValidateDatetime(dateInput, timeInput, targetNumber, targetName) {
            const message = validateDatetime(
                dateInput.value && timeInput.value ? `${dateInput.value} ${timeInput.value}` : null,
                targetNumber,
                targetName
            )

            if (message) {
                setValidationErrorMessage(message, dateInput)
                dateInput.setCustomValidity(message)
            }

        }

        /**
        * validate datetime number
        * @param {null|string} message
        * @param {HTMLElement} input
        * @return {void}
        */
        function setValidationErrorMessage(message, input) {
            const parent = input.closest('.form-group')
            const parentLastChild = parent.lastElementChild

            if (parentLastChild.tagName === 'SPAN' && parentLastChild.className.includes('invalid-feedback')) {
                parentLastChild.lastElementChild.textContent = message
            } else {
                const span = document.createElement('span')
                span.classList.add('invalid-feedback', 'd-block')
                span.setAttribute('role', 'alert')
                const strong = document.createElement('strong')
                strong.textContent = message
                span.appendChild(strong)

                parent.appendChild(span)
            }
        }

        /**
        * validate datetime number
        * @param {null|string} value
        * @param {null|number} targetNumber
        * @param {null|string} targetName
        * @return {null|string}
        */
        function validateDatetime(value, targetNumber, targetName) {
            if (!value) {
                return null;
            }
            if ((targetNumber !== null) && !validateDatetimeNumber(value, targetNumber)) {
                return 'invalid select weekday.'
            }

            if (targetName) {
                const targetDateInput = document.getElementById(`${targetName}_date`)
                const targetTimeInput = document.getElementById(`${targetName}_time`)
                if (targetDateInput && targetTimeInput) {
                    if (!validateGreaterThan(value, `${targetDateInput} ${targetTimeInput}`)) {
                        return 'lesser than target.'
                    }
                }
            }

            return null
        }

        /**
        * validate datetime number
        * @param {string} datetime
        * @param {null|number} targetNumber
        * @return {boolean}
        */
        function validateDatetimeNumber(datetime, targetNumber) {
            return (new Date(datetime).getDay()) === targetNumber
        }

        /**
        * validate datetime greater
        * @param {string} datetime
        * @param {null|string} targetDatetime
        * @return {boolean}
        */
        function validateGreaterThan(datetime, targetDatetime) {
            return new Date(targetDatetime).getTime() < new Date(datetime).getTime()
        }

    </script>
@stop
