<section class="col-lg-8 col-lg-offset-4">
<article class="panel panel-primary">
    <section class="link-heading panel panel-heading">
        <h2><?= h($link->title) ?></h2>
        <date>Created on <?= h($link->created) ?></date>
        <?= $this->Form->postLink('', ['action' => 'delete', $link->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $link->id),
                 'class'=>'glyphicon glyphicon-trash delete-link']) ?> </li>
    </section>
    <section class="link-content panel-body">
        <?= $this->Text->autoParagraph(h($link->content)); ?>
    </section>
</article>
</section>
    

<!--<div class="links view large-10 medium-9 columns">
    <h2><?= h($link->title) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Title') ?></h6>
            <p><?= h($link->title) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($link->id) ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Created') ?></h6>
            <p><?= h($link->created) ?></p>
            <h6 class="subheader"><?= __('Modified') ?></h6>
            <p><?= h($link->modified) ?></p>
        </div>
    </div>
    <div class="row texts">
        <div class="columns large-9">
            <h6 class="subheader"><?= __('Content') ?></h6>
            <?= $this->Text->autoParagraph(h($link->content)); ?>

        </div>
    </div>
</div>-->
