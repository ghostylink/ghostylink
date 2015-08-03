<section class="col-lg-12">
    <aside class="link-stats panel panel-info">
        <h2 class="panel-heading">My created links</h2>
        <div class="panel-body">
            <table>
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('title') ?></th>
                        <th><?= $this->Paginator->sort('content') ?></th>
                        <th class="actions"><?= __('Token') ?></th>
                        <th class="actions"><?= __('Life percentage') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $l): ?>
                        <tr>
                            <td><?= $this->Number->format($l->id) ?></td>
                            <td><?= h($l->title) ?></td>
                            <td><?= h($l->content) ?></td>
                            <td><?= $this->Html->link($l->token, '/' . $l->token) ?></td>
                            <td><?= $this->Number->format($l->life_percentage) ?> %</td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
</section>