<div id="left-block" class="col-lg-6">
    <?= $this->Html->image("logos/ghostylink-logo-300x250.png", array('class' => 'logo', 'alt' => 'ghostylink logo'));?>
</div>
<div class="actions columns col-lg-6">
<!--    <h3><?= __("Cache me if you can...") ?></h3>-->
    <?php echo $this->element('Link/add'); ?>
    <!--<?= $this->Html->link(__('New Link'), ['action' => 'add']); ?>-->
    
</div>