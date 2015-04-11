<div class="actions columns large-2 medium-3">
    <h3><?= __("Cache me if you can...") ?></h3>
    <?php echo $this->element('Link/add'); ?>
    <!--<?= $this->Html->link(__('New Link'), ['action' => 'add']); ?>-->
</div>
<!--<div class="links index large-10 medium-9 columns">
    <table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th><?= $this->Paginator->sort('modified') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($links as $link): ?>
        <tr>
            <td><?= $this->Number->format($link->id) ?></td>
            <td><?= h($link->title) ?></td>
            <td><?= h($link->created) ?></td>
            <td><?= h($link->modified) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $link->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $link->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $link->id], ['confirm' => __('Are you sure you want to delete # {0}?', $link->id)]) ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>-->
