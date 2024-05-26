@props([
    'class' => '',
    'name' => '',
    'value' => null,
    'required' => false,
])
<div class="form-group col-md-6">
    <div class="d-flex flex-column">
        <div class="icheck-danger">
            <input type="checkbox" checked="" id="checkboxDanger1" name={{$name}} value="1">
            <label for="checkboxDanger1">checkbox label1</label>
        </div>
        <div class="icheck-danger">
            <input type="checkbox" id="checkboxDanger2" name={{$name}} value="2">
            <label for="checkboxDanger2">checkbox label2</label>
        </div>
        <div class="icheck-danger">
            <input type="checkbox" disabled="" id="checkboxDanger3" name={{$name}} value="3">
            <label for="checkboxDanger3">checkbox label3</label>
        </div>
    </div>
</div>

