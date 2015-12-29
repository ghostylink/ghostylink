<div id="id_death_time"  class="input">
    <label>Time before deletion:</label><br/>
    <?php
    $options = array(['text' => '1 day', 'value' => 1, 'checked' => 'checked'],
        ['text' => '1 week', 'value' => 7],
        ['text' => '1 month', 'value' => 30]);
    $attributes = ['nestedInput' => false];
    $this->Form->templates(['nestingLabel' =>  '<label {{attrs}}>{{text}}</label>']);
    echo $this->Form->radio('death_time', $options, $attributes);
    ?>
</div>