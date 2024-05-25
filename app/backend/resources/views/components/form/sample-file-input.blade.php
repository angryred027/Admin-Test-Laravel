@props([
    'class' => '',
    'name' => '',
    'value' => null,
    'required' => false,
    'isMultiple' => false,
    'isPreview' => false,
])
<div class="">
    <div id="{{$name . '_input-file-area'}}" class="upload-area d-flex justify-content-center @error($name . '_input-files') is-invalid @enderror">
        <div class="d-flex justify-content-center flex-column">
            <i class="fas fa-cloud-upload-alt fa-5x"></i>
            <p>Click OR Drag and drop a file</p>
        </div>
        <input
            type="file"
            id="{{$name . '_input-files'}}"
            name="{{$name}}"
            value="{{$value ?? ''}}"
            {{$required ? 'required' : ''}}
            {{$isMultiple ? 'multiple' : ''}}
            class="upload_file @error($name . '_input-files') is-invalid @enderror"
        >
    </div>
    @if ($isPreview)
        <div id="{{$name . '_preview-image'}}" class="preview-image"></div>
    @endif
    @error ($name . '_input-files')
        <span class="invalid-feedback d-block" role="alert">
            <p>{{$message}}</p>
        </span>
    @enderror
    <div id="{{$name . '_file-name-area'}}" class="upload_file_name_area upload_file_name_area_no_uploaded my-2">
        <span id="{{$name . '_file-name'}}" class="upload_file_name"></span>
    </div>
</div>

@section('css')
    @parent
    <style>
        .upload-area {
            margin: auto;
            width: 85%;
            height: 200px;
            position: relative;
            border: 2px dotted rgba(0, 0, 0, .4);
        }
        .upload-area.invalid {
            color: #dc3545;
            border-color: #dc3545;
        }
        .upload-area i {
            opacity: .1;
            width: 100%;
            text-align: center;
        }
        .upload-area p {
            width: 100%;
            opacity: .8;
        }

        .upload-area_dragover {
            background-color: rgba(0, 0, 0, .6);
            border: 4px dotted rgba(0, 0, 0, .4);
        }

        .upload-area_uploaded {
            display: none !important;
        }

        .upload_file {
            top: 0;
            left: 0;
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;

            &:hover {
                cursor: pointer;
            }
        }

        .upload_file_name_area_no_uploaded {
            display: none;
        }

        .upload_file_name {
            /* &:hover {
                cursor: pointer;
            }
            */
        }

        .upload_file_name_reset-file-icon {
            padding: .0rem .25rem;

            &:hover {
                cursor: pointer;
                border-color: #5f6674;
            }
            /*
            color: #ff0000;
            padding: 0 8px;
            font-size: 16px;
            border: 1px solid #c6c6c6;
            border-radius: 10px;

            &:hover {
              cursor: pointer;
              border-color: #5f6674;
            }
            */
        }

        .preview-image img {
            height: 100px;
        }

    </style>
@stop

@section('js')
    @parent
    <script>
        console.log('Sample File Input');

        // const isMultiple = !!`{{$isMultiple}}`
        // const isPreview = !!`{{$isPreview}}`

        // const fileArea = document.getElementById('input-file-area');
        // const fileInput = document.getElementById('input-files');

        initFileInputComponent(`{{$name}}`, !!`{{$isMultiple}}`, !!`{{$isPreview}}`);

        /**
        * initialize
        * @param {string} name
        * @param {boolean} isMultiple
        * @param {boolean} isPreview
        * @return {void}
        */
        function initFileInputComponent(name, isMultiple, isPreview) {
            const fileInputAreaId = name + '_input-file-area'
            const fileInputId = name + '_input-files'
            const fileNamAreaId = name + '_file-name-area'
            const fileNameId = name + '_file-name'
            const fileResetId = name + '_file-reset'
            const previewImageId = name + '_preview-image'
            const previewChildImageId = name + '_preview-image-image'

            const fileArea = document.getElementById(fileInputAreaId);
            const fileInput = document.getElementById(fileInputId);
            const fileNameArea = document.getElementById(fileNamAreaId);
            const fileNameContents = document.getElementById(fileNameId);
            const preview = document.getElementById(previewImageId);
            // const previewChildImage = document.getElementById(previewChildImageId);

            fileInput.addEventListener('change', function(evt){
                console.log('change: ');
                // console.log('change2: ' + JSON.stringify(fileInput.files, null ,2));

                evt.preventDefault();
                const files = fileInput.files;
                // ファイルをアップロードした時
                if (files) {
                    let fileName = ''
                    for(i = 0; i < files.length; i++) {
                        if (fileName !== '') {
                            fileName += ','
                        }
                        fileName += files[i].name
                    }

                    fileNameContents.textContent = fileName
                    setResetButton(
                        fileInputAreaId,
                        fileResetId,
                        fileNameId,
                        previewImageId,
                        previewChildImageId,
                        fileArea,
                        fileInput,
                        preview,
                        fileNameArea,
                        fileNameContents,
                        isMultiple,
                        isPreview
                    )

                    if (!isMultiple && isPreview) {
                        setImage(previewChildImageId, files[0], fileArea, fileInput, fileNameContents, preview)
                    }

                    fileArea.classList.add('upload-area_uploaded');
                }
            });

            fileArea.addEventListener('dragover', function(evt){
                console.log('dragover: ');
                evt.preventDefault();
                fileArea.classList.add('upload-area_dragover');
            });

            fileArea.addEventListener('dragleave', function(evt){
                console.log('dragleave: ');
                evt.preventDefault();
                fileArea.classList.remove('upload-area_dragover');
            });
            fileArea.addEventListener('drop', function(evt){
                console.log('drop: ');
                evt.preventDefault();
                fileArea.classList.remove('upload-area_dragover');
                const files = evt.dataTransfer.files;
                if (!isMultiple && files.length > 1) {
                    const errorMessage = 'drooped multi files'
                    alert(errorMessage)
                    throw new Error(errorMessage)
                }
                fileInput.files = files;

                let fileName = ''
                for(i = 0; i < files.length; i++) {
                    if (fileName !== '') {
                        fileName += ','
                    }
                    fileName += files[i].name
                }

                fileNameContents.textContent = fileName
                setResetButton(
                    fileInputAreaId,
                    fileResetId,
                    fileNameId,
                    previewImageId,
                    previewChildImageId,
                    fileArea,
                    fileInput,
                    preview,
                    fileNameArea,
                    fileNameContents,
                    isMultiple,
                    isPreview
                )

                if (!isMultiple && isPreview) {
                    setImage(previewChildImageId, files[0], fileArea, fileInput, fileNameContents, preview)
                }
                fileArea.classList.add('upload-area_uploaded');
            });
        }

        /**
        * create reset button
        * @param {string} fileInputAreaId
        * @param {string} fileResetId
        * @param {string} fileNameId
        * @param {string} previewImageId
        * @param {string} previewChildImageId
        * @param {HTMLElement} fileArea
        * @param {HTMLElement} fileInput
        * @param {HTMLElement} preview
        * @param {HTMLElement} fileNameArea
        * @param {HTMLElement} fileNameContents
        * @param {boolean} isMultiple
        * @param {boolean} isPreview
        * @return {void}
        */
        function setResetButton(
            fileInputAreaId,
            fileResetId,
            fileNameId,
            previewImageId,
            previewChildImageId,
            fileArea,
            fileInput,
            preview,
            fileNameArea,
            fileNameContents,
            isMultiple,
            isPreview,
        ) {
            // 親要素の表示
            fileNameArea.classList.remove('upload_file_name_area_no_uploaded')

            const tmpResetButton = document.createElement('span')
            tmpResetButton.textContent = 'x'
            tmpResetButton.classList.add(
                'btn-light',
                'text-secondary',
                'btn-xs',
                'rounded-circle',
                'font-monospace',
                'ml-1',
                'upload_file_name_reset-file-icon'
            );
            tmpResetButton.setAttribute('id', fileResetId)

            fileNameContents.classList.add('btn-secondary', 'btn-sm', 'rounded-pill');

            fileNameContents.appendChild(tmpResetButton)

            tmpResetButton.addEventListener('click', function(evt){
                evt.preventDefault();
                console.log('click: ');

                fileInput.files = null
                fileInput.value = null
                fileNameContents.textContent = null
                fileNameArea.classList.add('upload_file_name_area_no_uploaded')

                if (!isMultiple && isPreview) {
                    console.log('remove image: ');
                    const tmpImg = document.getElementById(previewChildImageId)
                    tmpImg.remove()
                }

                fileArea.classList.remove('upload-area_uploaded');
                tmpResetButton.remove()

            });
        }

        /**
        * set preview image
        * @param {string} previewChildImageId
        * @param {File} file
        * @param {HTMLElement} fileArea
        * @param {HTMLElement} fileInput
        * @param {HTMLElement} fileNameContents
        * @param {HTMLElement} preview
        * @return {void}
        */
        function setImage(previewChildImageId, file, fileArea, fileInput, fileNameContents, preview) {
            // ファイルの読み込み
            const reader = new FileReader()
                reader.onload = () => {
                    ImageData = reader.result?.toString();
                    console.log('ImageData: ' + ImageData);

                    const tmpImg = document.createElement('img')
                    tmpImg.setAttribute('src', ImageData)
                    tmpImg.setAttribute('id', previewChildImageId)
                    preview.appendChild(tmpImg)
            }
            reader.readAsDataURL(file);
        }
    </script>
@stop
