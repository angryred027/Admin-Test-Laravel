@props(['multiple' => false])
<div id="input-file-area" class="upload-area d-flex justify-content-center">
    <div class="d-flex justify-content-center flex-column">
        <i class="fas fa-cloud-upload-alt fa-5x"></i>
        <p>Click OR Drag and drop a file</p>
    </div>
    <input type="file" name="upload_file" id="input-files">
</div>

@section('css')
    <style>
        .upload-area {
            margin: auto;
            width: 85%;
            height: 200px;
            position: relative;
            border: 2px dotted rgba(0, 0, 0, .4);
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

        #input-files {
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

    </style>
@stop

@section('js')
    <script>
        console.log('Sample File Input');

        const fileArea = document.getElementById('input-file-area');
        const fileInput = document.getElementById('input-files');

        let ImageData = null;

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
            fileInput.files = files;

            // TODO 検証用
            console.log('files: ' + JSON.stringify(files, null, 2));
            console.log('files.length: ' + files.length);
            console.log('file: ' + JSON.stringify(files[0], null, 2));
            file = files[0];
            console.log('file.size: ' + file.size);
            console.log('file.type: ' + file.type);
            console.log('file.name: ' + file.name);

            // ファイルの読み込み
            const reader = new FileReader()
                reader.onload = () => {
                    ImageData = reader.result?.toString();
                    console.log('ImageData: ' + ImageData);
            }
            reader.readAsDataURL(file);
        });

    </script>
@stop
