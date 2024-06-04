@props([
    'class' => '',
    'name' => '',
    'id' => '',
    'submitId' => '',
    'enctype' => 'multipart/form-data',
    'action' => '',
    'value' => null,
    'optionList' => [],
    'required' => false,
    'disabled' => false,
])
<form id="{{$id}}" method="POST" @if (!empty($enctype)) "enctype=$enctype" @endif action={{$action}}>
    @csrf
    {{ $slot }}

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <x-adminlte-button id="{{$submitId}}" label="Submit" type="submit" theme="success" icon="fas fa-thumbs-up"/>
            </div>
        </div>
    </div>
</form>

@section('js')
    @parent
    <script>
        // TODO initFormComponent() の処理
        // initFormComponent('{{$id}}', '{{$submitId}}');
    </script>
@stop
