<?php
    $this->start('script');
    echo $this->Html->script("Links/index");    
    echo $this->Html->script("Links/components");
    $this->end(); 
        
    ?>
<div id="left-block" class="col-lg-6">
    <?= $this->Html->image("logos/ghostylink-logo-300x250.png", array('class' => 'logo', 'alt' => 'ghostylink logo'));?>
<section class="panel panel-info col-lg-12 link-components">
    
    <h2 class="panel panel-heading ">Choose component to add to your link</h2>
    <div class="panel-body">
        <ul id="link-components-available">
            <li data-related-field="death_time" class="glyphicon glyphicon-time label label-primary ui-widget-header"
                data-field-html="<?= htmlspecialchars($this->Form->input('death_time', ['type' => 'number',
                                              'id' => 'inputContent',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter your links life expectancy (number of days)",
                                              'required' => 'false'])); ?>"> Time limit</li>
            <li data-related-field="max_views" class="glyphicon glyphicon-eye-open label label-primary ui-widget-header"
                data-field-html="<?= htmlspecialchars($this->Form->input('max_views', ['type' => 'number',
                                              'id' => 'inputContent',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter your links life expectancy (number of views)",
                                              'required' => 'false'])); ?>"> Views limit</li>            
        </ul>
    </div>
</section>
</div>
<div class="actions columns col-lg-6">
<!--    <h3><?= __("Cache me if you can...") ?></h3>-->
    <?php echo $this->element('Link/add'); ?>
    <!--<?= $this->Html->link(__('New Link'), ['action' => 'add']); ?>-->
    
</div>
</div>

