<?php
    $this->start('script');
    echo $this->Html->script("Links/life");
    $this->end(); 
    
    $this->start('css');
    echo $this->Html->css("Links/view");
    $this->end();
?>
<div class="row">
    <aside class="link-stats panel panel-default col-lg-4">
        <h2 class="panel-heading">Link statistics</h2>
        <div class="panel-body">
            <?= $this->element("Link/life_percentage",array("link"=>$link)); ?>
            <?= $this->element("Link/remaining_views",array("link"=>$link)); ?>
        </div>
    </aside>
    <section class="col-lg-8">
        <article class="panel panel-primary">
            <section class="link-heading panel panel-heading">
                <h2><?= h($link->title) ?></h2>
                <time>Created on <?= h($link->created) ?></time>
                <?= $this->Form->postLink('', ['action' => 'delete', $link->id],
                        ['confirm' => __("Are you sure you want to delete ' {1} '?", $link->id, $link->title),
                         'class'=>'glyphicon glyphicon-trash delete-link']) ?> </li>
            </section>
            <section class="link-content panel-body">
                <?= $this->Text->autoParagraph(h($link->content)); ?>
            </section>
        </article>
    </section>
</div>
