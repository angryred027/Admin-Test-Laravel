@props([
    'class' => '',
    'name' => '',
    'value' => null,
    'required' => false,
])
<div class="form-group clearfix col-md-6">
    <div class="d-flex flex-column">
        <div class="icheck-primary">
            <input type="radio" id="radioPrimary1" name={{$name}} checked="" value="1">
            <label for="radioPrimary1">radio label1</label>
        </div>
        <div class="icheck-primarye">
            <input type="radio" id="radioPrimary2" name={{$name}} value="2">
            <label for="radioPrimary2">radio label2</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="radioPrimary3" name={{$name}} value="3">
            <label for="radioPrimary3">radio label3</label>
        </div>
    </div>
</div>
