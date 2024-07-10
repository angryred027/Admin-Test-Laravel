@extends('admin.layout')

@section('title', 'Dashboard')

@section('content_header')
    @parent
    <h1>Test Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful test admin panel.</p>

    <p>{{$subTitle}}</p>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <form id="createForm" method="POST" enctype="multipart/form-data" action={{route('admin.sampleImageUploader1.createModal.post')}}>
                    @csrf
                    <x-adminlte-input name="name" label="name" placeholder="name" fgroup-class="col-md-6" value={{$name}}/>

                    {{--  検証用  --}}
                    <x-form.sample-checkbox
                        name="testCheckbox"
                        :valueList="[2]"
                        :optionList="['label1' => 1, 'label2' => 2, 'label3' => 3]"
                        {{--  :optionList="['label1' => '1', 'label2' => '2', 'label3' => '3']"  --}}
                        :required="true"
                    />

                    <x-form.sample-radio-button
                        name="testRadioButton"
                        {{--  value="1"  --}}
                        :optionList="['label1' => 1, 'label2' => 2, 'label3' => 3]"
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

                    <div class="form-group col-md-6">
                        <label for="testImage">inputDate</label>
                        <x-form.sample-file-input name="testFileTest" value="" :isPreview="true" :isMultiple="false" />
                    </div>

                    <div class="form-group col-md-6 my-2">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm">
                                    {{--  <x-adminlte-button id="createFormButton" label="Submit" type="submit" theme="success" icon="fas fa-thumbs-up" disabled/>  --}}
                                    {{--  <x-adminlte-button id="createFormButton" label="Submit" type="submit" theme="success" icon="fas fa-thumbs-up" class="form_submit_button" />  --}}
                                    {{--  <button type="button" class="btn btn-info form_submit_button" id="createFormButton1">
                                        <i class="fas fa-cloud"></i>TestOpenModal
                                    </button>  --}}
                                    <button type="button" class="btn btn-info form_submit_button" id="createFormButton1">
                                        <i class="fas fa-cloud"></i>TestOpenModal
                                    </button>
                                    <button type="button" class="btn btn-primary form_submit_button" data-toggle="modal" data-target="#testModal_modal">
                                        Launch demo modal
                                    </button>
                                    <x-message.sample-modal name="testModal" title="testTitle" />
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
                <x-adminlte-button label="Back" theme="secondary" icon="fas fa-arrow-left"  onclick="location.href='{{route('admin.sampleImageUploader1')}}'"/>
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
