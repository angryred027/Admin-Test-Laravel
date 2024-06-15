@extends('admin.base')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Test Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful test admin panel.</p>

    <p>{{$subTitle}}</p>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <form id="createForm" method="POST" enctype="multipart/form-data" action={{route('admin.sampleImageUploader1.post')}}>
                    @csrf
                    <x-adminlte-input name="name" label="name" placeholder="name" fgroup-class="col-md-6" value={{$name}}/>

                    {{--  検証用  --}}
                    <x-form.sample-checkbox
                        name="testCheckbox"
                        :valueList="[2]"
                        :optionList="[1 => 'label1', 2 => 'label2', 3 => 'label3']"
                        :required="true"
                    />

                    <x-form.sample-radio-button
                        name="testRadioButton"
                        :optionList="[1 => 'label1', 2 => 'label2', 3 => 'label3']"
                        :required="true"
                    />

                    <x-form.sample-select name="testSelet1" value="" label="testSelet1" placeholder="placeholder" :options="[1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3']"/>
                    {{--  <x-adminlte-select name="testSelet1" label="testSelect1" fgroup-class="col-md-6">
                        <x-adminlte-options :options="[1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3']" disabled="1"
                            empty-option="Select an option..."/>
                    </x-adminlte-select>  --}}
                    <x-form.sample-input-date name="start_time" value="{{\App\Library\Time\TimeLibrary::getCurrentDateTime('Y-m-d')}}"/>
                    <x-form.sample-input-date name="end_time" value="" targetName="start_time" :isSetLimitTime="true"/>

                    <x-form.sample-text-area name="testTestArea" value=""/>

                    {{--  <div class="form-group col-md-6">
                        <label for="testDate">inputDate</label>
                        <div class="input-group @error('testDate') adminlte-invalid-igroup @enderror  @error('testTime') adminlte-invalid-igroup @enderror">
                            <input name="testDate" type="date" placeholder="input date" label="testDate" required="true" class="form-control @error('testDate') is-invalid @enderror"/>
                            <input name="testTime" type="time" placeholder="HH:mm" label="testTime" required="true" class="form-control @error('testTime') is-invalid @enderror"/>
                        </div>
                        @error('testDate')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                        @enderror
                        @error('testTime')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                        @enderror
                    </div>  --}}
                    {{--  <div class="form-group col-md-6">
                        <label for="exampleFormControlFile1">Example file input</label>
                        <input type="file" name="exampleFormControlFile1" placeholder="input file" :value=$image class="form-control-file">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleFormControlFile1">Example file input</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="exampleFormControlFile1" placeholder="input file" :value=$image class="form-control-file">
                                <label class="custom-file-label text-truncate" for="file"></label>
                            </div>
                        </div>
                    </div>
                    <x-adminlte-input-file name="file" label="upload file" fgroup-class="col-md-6" :value="$image"/>  --}}
                    {{--  <x-adminlte-select2 name="testSelet2" label="testSelect2" fgroup-class="col-md-6">
                        <option>Option 1</option>
                        <option disabled>Option 2</option>
                        <option selected>Option 3</option>
                    </x-adminlte-select2>  --}}

                    <div class="form-group col-md-6">
                        <label for="testImage">inputDate</label>
                        <x-form.sample-file-input name="testFileTest" value="" :isPreview="true" :isMultiple="false" />
                    </div>

                    <div class="form-group col-md-6 my-2">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm">
                                    {{--  <x-adminlte-button id="createFormButton" label="Submit" type="submit" theme="success" icon="fas fa-thumbs-up" disabled/>  --}}
                                    <x-adminlte-button id="createFormButton" label="Submit" type="submit" theme="success" icon="fas fa-thumbs-up" class="form_submit_button" />
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <x-adminlte-button label="Danger" theme="danger" icon="fas fa-ban"/>
                <x-adminlte-button label="Back" theme="secondary" icon="fas fa-thumbs-down" onclick="location.href='{{route('admin.sampleImageUploader1')}}'"/>
                <x-adminlte-button label="Back" theme="secondary" icon="fas fa-arrow-left"  onclick="location.href='{{route('admin.sampleImageUploader1')}}'"/>
                <x-adminlte-button label="Success" theme="success" icon="fas fa-thumbs-up"/>
            </div>
        </div>
    </div>
@stop

@section('adminlte_css')
    @parent
    <style>
        /* form内のinputにバリデーションエラーがあればボタン非活性化 */
        form:has(input:invalid, textarea:invalid, select:invalid) .form_submit_button {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
@stop

@section('js')
    @parent
    <script>
        // TODO checkboxとの兼ね合いの為、一時コメントアウト
        // initFormComponent('createForm', 'createFormButton');

        /**
        * initialize
        * @param {string} id
        * @param {string} submitId
        * @return {void}
        */
        function initFormComponent(id, submitId) {
            const submitButton = document.getElementById(submitId);
            let inputList = document.querySelectorAll(`#${id} input`);
            const textareaList = document.querySelectorAll(`#${id} textarea`);
            const selectList = document.querySelectorAll(`#${id} select`);

            inputList = [...inputList, ...textareaList, ...selectList];

            // checkbox
            const checkboxNameValueDict = {}
            for (const input of inputList) {
                if (input.getAttribute('type') !== 'checkbox') {
                    continue
                }
                const name = input.getAttribute('name')
                if (!checkboxNameValueDict[name]) {
                    checkboxNameValueDict[name] = []
                }
                checkboxNameValueDict[name].push(input.value)
            }

            let isValid = false;
            // すべてのinput要素の入力中にバリデーションをチェックする
            for (const input of inputList) {
                input.addEventListener('change', () => {
                    // バリデーション状態の結果に応じてボタンの活性状態を切り替え
                    submitButton.disabled = !isValidateInput(inputList, checkboxNameValueDict)
                });
            }
        }

        /**
        * validate input.
        * @param {NodeList<Element>} inputList
        * @param {Record<string, number[]>} checkboxNameValueDict
        * @return {boolean}
        */
        function isValidateInput(inputList, checkboxNameValueDict) {
            const validList = [];
            const validatedCheckboxDict = []
            for (const input of inputList) {
                let isValid = false
                if (input.getAttribute('type') === 'checkbox') {
                    const name = input.getAttribute('name')
                    if (validatedCheckboxDict[name]) {
                        continue
                    }

                    if (name && checkboxNameValueDict[name]) {
                        isValid = validateCheckbox(name.slice(0, -2), checkboxNameValueDict[name])
                        validatedCheckboxDict[name] = 1
                    }
                } else {
                    isValid = input.checkValidity()
                }

                validList.push(isValid);
                if (!isValid) {
                    break
                }
            }
            return validList.every((v) => !!v);
        }
    </script>
@stop
