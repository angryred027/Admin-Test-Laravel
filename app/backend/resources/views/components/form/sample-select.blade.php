<x-adminlte-select
    name="{{$name}}"
    value="{{$value}}"
    label={{$label}}
    placeholder="{{$placeholder}}"
    required="true"
    fgroup-class="col-md-6 {{$class??''}}"
>
    <x-adminlte-options :options="$options" disabled="1" empty-option="--"/>
</x-adminlte-select>
