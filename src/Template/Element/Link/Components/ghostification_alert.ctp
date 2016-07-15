<div class="input required">    
    <label for="default_threshold">Life percentage alert threshold</label>
    <div id="slider-default_threshold"></div>
    <?=
    $this->Form->input('alert_parameter.life_threshold', [
        'id' => 'default_threshold',
        'label' => false,
        'placeholder' => "Default link alert life threshold",
        'readonly' => true,
        'type' => 'text',
        'value' =>$this->request->session()->read('Auth.User.default_threshold'),
        'required' => 'false']);
    ?>

</div>
