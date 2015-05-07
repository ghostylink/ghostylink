<?php
    $this->start('script');
    echo $this->Html->script("Links/components");
    $this->end();
?>
<div class="links form col-lg-10 col-md-9">
    <?php
    if (!isset($link)){
        echo $this->Form->create('Link', ['action' => 'add']);
    }
    else {
        echo $this->Form->create($link);
    }
    ?>
    <legend>Create your ghost</legend>
    <fieldset>
        <?php
            echo $this->Form->input('title', ['type' => 'text',
                                              'id' => 'inputTitle',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter a title",
                                              'required' => 'false']);            
            echo $this->Form->input('content', ['type' => 'textarea',
                                              'id' => 'inputContent',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter your private contents",
                                              'required' => 'false']);?>
        <label>Your components</label><ul id="link-components-chosen" class="col-lg-12">
            <span class="legend">Drop some components here</span>
        </ul>    
        <?php
//            echo $this->Form->input('max_views', ['type' => 'number',
//                                              'id' => 'inputContent',
//                                              'class' => 'form-control', 
//                                              'placeholder' => "Enter your links life expectancy (number of views)",
//                                              'required' => 'false']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Create the link'), ['type' => 'submit',
                                                   'class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>    
</div>
