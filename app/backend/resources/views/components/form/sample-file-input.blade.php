<div
    class="parts-simple-file-input $className ? ' ' + $className : '' $isDraged ? ' parts-simple-file-input__drag_on' : ''"
    onDragOver=changeDragedStateHandler(e, true)
    onDrop=dropFileHandler
    onDragLeave=changeDragedStateHandler(e, false)
    onDragEnd=changeDragedStateHandler(e, false)
>
    <div
        class="parts-simple-file-input__drop-area $isDraged ? ' parts-simple-file-input__drag_on' : ''"
        onDragOver=changeDragedStateHandler(e, true)
        onDrop=dropFileHandler
        onDragLeave=changeDragedStateHandler(e, false)
        onDragEnd=changeDragedStateHandler(e, false)
        >
        @if ($value) {
            @if ($isOpenPreview)
                <div class="parts-simple-file-input__selected-image-file">
                <img
                    src={imageData}
                    width="150"
                    // async
                    alt=""
                    loading="lazy"
                />
                <span
                    class="parts-simple-file-input__reset-file-icon"
                    onClick=resetFileHandler
                >
                    ×
                </span>
                </div>
            @else
                <div class="parts-simple-file-input__selected-file">
                    <span class="parts-simple-file-input__file-name">
                        <span>{value.name}</span>
                        <span
                        class="parts-simple-file-input__reset-file-icon"
                        onClick=resetFileHandler
                        >
                        ×
                        </span>
                    </span>
                </div>

            @endif
        }
        @else
            <label>
                <span class="parts-simple-file-input__form-label">
                    {{$formLabel ?? 'ファイルの選択'}}
                </span>
                <input
                    class="parts-simple-file-input className ? ' ' + className : ''"
                    ref=refElement
                    type="file"
                    accept=accept
                    onInput=inputEventHandler
                    required=required
                    disabled=disabled
                    readOnly=readOnly
                />
            </label>
        @endif

    </div>
    @if ($isFileValidationError ?? false)
        <p className="parts-simple-file-input__error-text">errorText</p>
    @endif
</div>

@section('css')
    <style>
.parts-simple-file-input {
  label {
    font-size: 12px;
    padding: 2px 3px;
  }

  label:hover {
    cursor: pointer;
  }

  label input {
    display: none;
  }

  &__form-label {
    color: rgb(117, 117, 117);
  }

  &__file-name {
    font-size: 14px;
    margin-left: 20px;
    color: rgb(117, 117, 117);
  }

  &__reset-file-icon {
    color: #ff0000;
    padding: 0 4px;
    font-size: 12px;
    border: 1px solid #c6c6c6;
    border-radius: 10px;

    &:hover {
      cursor: pointer;
      border-color: #5f6674;
    }
  }

  &__selected-file {
    font-size: 12px;
    padding: 2px 3px;
    word-break: break-all;
  }

  /* &__selected-image-file {} */

  &__error-text {
    color: #d70035;
  }

  &__drop-area {
    width: 100%;
    padding: 10px;
    text-align: center;
    border: 1px dashed #c6c6c6;
    background-color: #f9f9f9;
    border-radius: 2px;
  }

  &__drag_on {
    border: 2px dashed #bcbcbc;
    background-color: #fafdff;
  }
}
    </style>
@stop

@section('js')
    <script>
        console.log('Sample File Input');

        let [isFileValidationError, setIsFileValidationError]
        let errorText = $errorText ?? ''
        let isFileValidationError = $isFileValidationError ?? ''

  // mount後に実行する処理
  const onDidMount = (): void => {
    // プレビュー設定ありかつファイルデータがある場合
    if (isOpenPreview && value) {
      createPreviewImage(value)
    }
  }
  useEffect(onDidMount, [])

  // methods
  /**
   * chcek file validatiaon
   * @param {FileList} files
   * @return {void}
   */
  const checkFileValidationHandler = (files: FileList): void => {
    if (!checkFileLength(files.length, fileLength)) {
      setIsFileValidationError(true)
      setErrorText('invalid file length')
      return
    }

    // 下記の形で配列にも出来る
    // const fileList = Array.from(files)
    Object.keys(files).forEach((key: string) => {
      let accepts: undefined | string[]
      if (accept.includes(',')) {
        accepts = accept.split(',')
      }
      const file = files[parseInt(key)]
      if (!checkFileSize(file.size, fileSize)) {
        setIsFileValidationError(true)
        setErrorText('invalid file size')
      } else if (!checkFileType(file.type, accepts ?? accept)) {
        setIsFileValidationError(true)
        setErrorText('invalid file type')
      } else {
        setIsFileValidationError(false)
        setErrorText('')
      }
    })
  }

  /**
   * create preview image
   * @param {File} file
   * @return {void}
   */
  const createPreviewImage = (file: File): void => {
    const reader = new FileReader()
    reader.onload = () => {
      setImageData(reader.result?.toString())
    }
    reader.readAsDataURL(file)
  }

  /**
   * input event handler
   * @param {Event} event
   * @return {void}
   */
  const inputEventHandler = (
    event: HTMLElementEvent<HTMLInputElement>
  ): void => {
    const data = event.target.files ? event.target.files : undefined

    if (data) {
      checkFileValidationHandler(data)

      if (!isFileValidationError) {
        // update emit
        onUpdateFile(data[0])

        if (isOpenPreview) {
          createPreviewImage(data[0])
        }
      }
    }
  }

  /**
   * reset input file
   * @param {Event} event
   * @return {void}
   */
  const resetFileHandler: MouseEventHandler<HTMLSpanElement> = (
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    _: MouseEvent<HTMLSpanElement>
  ): void => {
    // reset emit
    onResetFile()
    setIsFileValidationError(false)
    setErrorText('')
  }

  /**
   * change file data by drag event
   * @param {DragEvent} event
   * @return {void}
   */
  const changeFileByDropEvent = (event: DragEvent): void => {
    if (event.dataTransfer?.files) {
      const files = event.dataTransfer?.files
      checkFileValidationHandler(files)
      // const data = event.target.files ? event.target.files![0] : undefined
      if (!isFileValidationError) {
        // update emit
        onUpdateFile(files[0])

        if (isOpenPreview) {
          createPreviewImage(files[0])
        }
      }
    }
  }

  /**
   * change draged status
   * @param {DragEvent} dragEvent
   * @param {boolean} value
   * @return {void}
   */
  const changeDragedStateHandler = (
    dragEvent: DragEvent,
    value = false
  ): void => {
    // イベントの伝播の中断とデフォルトアクションの抑制
    const event = dragEvent as unknown as Event
    event.stopPropagation()
    event.preventDefault()

    setIsDraged(value)
  }

  /**
   * drop file handler
   * @param {DragEvent} event
   * @return {void}
   */
  const dropFileHandler = (dropEvent: DragEvent): void => {
    // イベントの伝播の中断とデフォルトアクションの抑制
    const event = dropEvent as unknown as Event
    event.stopPropagation()
    event.preventDefault()

    changeFileByDropEvent(dropEvent)
    changeDragedStateHandler(dropEvent)
  }
    </script>
@stop
