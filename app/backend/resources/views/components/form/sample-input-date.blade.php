<div class="sample-date-input form-group col-md-6">
    <label for="$name">inputDate</label>
    <div class="input-group @error('$name') adminlte-invalid-igroup @enderror  @error('testTime') adminlte-invalid-igroup @enderror">
        <input name="$name" type="date" placeholder="input date" label="$name" required="true" class="form-control @error($name) is-invalid @enderror"/>
        <input name={{$name . '_time'}} type="time" placeholder="HH:mm" label={{$name . '_time'}} required="true" class="form-control @error($name .'_time') is-invalid @enderror"/>
    </div>
    @error($name)
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{$message}}</strong>
        </span>
    @enderror
    @error($name . '_time')
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{$message}}</strong>
        </span>
    @enderror
</div>
