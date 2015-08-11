<section class="col-lg-12">
    <aside class="link-stats panel panel-info">
        <h2 class="panel-heading">My created links</h2>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('title') ?></th>
                        <th><?= $this->Paginator->sort('Date') ?></th>
                        <th><?= $this->Paginator->sort('content') ?></th>
                        <th class="actions"><?= __('Token') ?></th>
                        <th class="actions"><?= __('Life') ?></th>
                        <th class="actions"><?= __('More') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $l): ?>
                        <tr>
                            <?php; //TODO: remove id column  ?>
                            <td><?= $this->Number->format($l->id) ?></td>
                            <td><?= h($l->title) ?></td>
                            <td><?= h($l->created) ?></td>
                            <td><?= h($l->content) ?></td>
                            <td><?= $this->Html->link($l->token, '/' . $l->token) ?></td>
                            <td><?= $this->Number->format($l->life_percentage) ?> %</td>
                            <td title="<?= h($l->death_time) == '' ? 'None' : h($l->death_time) ?>
                                <?= h($l->max_views) == '' ? 'M' : h($l->max_views) ?>">See details</td>
                            <td><?php
                                if($l->status == true) {
                                    ?><?=
                                    $this->Form->postLink('', ['_name' => 'link-disable', $l->id], ['confirm' => __("Are you sure you want to disable : '") . $l->title . "' ?",
                                        'class' => 'btn btn-xs btn-warning glyphicon glyphicon-remove-sign disable-link', 'title' => 'Disable']);
                                    ?><?php
                                }
                                else {
                                    ?><?=
                                    $this->Form->postLink('', ['_name' => 'link-enable', $l->id], ['confirm' => __("Are you sure you want to enable : '") . $l->title . "' ?",
                                        'class' => 'btn btn-xs btn-success glyphicon glyphicon-ok-sign enable-link', 'title' => 'Enable']);
                                    ?><?php
                                }
                                ?><?=
                                $this->Form->postLink('', ['_name' => 'link-delete', $l->id], ['confirm' => __("Are you sure you want to delete : '") . $l->title . "' ?",
                                    'class' => 'btn btn-xs btn-danger glyphicon glyphicon-trash delete-link', 'title' => 'Delete']);
                                ?></td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
            <nav id="pagination" class="centered-text">
                <ul class="pagination" role="pagination">
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                </ul>
                <p><?= $this->Paginator->counter() ?></p>
            </nav>
        </div>
</section>