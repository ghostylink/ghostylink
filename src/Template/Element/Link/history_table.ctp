<section class="col-lg-12 panel panel-info">
    <h2 class="panel-heading">My created links</h2>
    <div class="panel-body table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>Status</th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th><?= __('Life') ?></th>
            <th><?= __('Components') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($history as $l):
            $classColor = '';
            if ($l->life_percentage > 66.6) {
                $classColor = 'life-danger';
            } else if ($l->life_percentage < 33.3) {
                $classColor = 'life-ok';
            } else {
                $classColor = 'life-warning';
            }
            ?>
            <tr class="<?php
            if (!$l->status) {
                echo 'disable';
            }
            ?>">
                <td>
                    <?php
                    if ($l->status) {
                        $classStatus = 'life-ok';
                        $titleStatus = "Public";
                    } else {
                        $titleStatus = "Disabled";
                        $classStatus = 'life-danger';
                    }
                    ?>
                    <span title="<?= $titleStatus ?>"
                          class="<?= $classStatus ?> glyphicon glyphicon-flag"></span>
                </td>
                <td title="<?= $l->title; ?>"><?= reduce_column($l->title) ?></td>
                <td><time class="utc"
                          data-utc-time="<?= $this->Time->format($l->created, 'MM/dd/YYYY hh:mm:ss a ') ?>UTC"><?= h($l->created) ?></time></td>
                <td class="life <?= $classColor ?>"><?= $this->Number->format($l->life_percentage) ?> %
                    <span class="glyphicon glyphicon-question-sign"
                          title="Death date:<?= h($l->death_time) == '' ? 'None' : h($l->death_time) ?> - Max views:
                    <?= h($l->max_views) == '' ? 'M' : h($l->max_views) ?>"></span>
                </td>
                <td>
                    <?= $this->element("Link/Components/list", ["link" => $l]); ?>
                </td>
                <td class="actions"><?php
                    if ($l->alert_parameter) {
                        if ($l->alert_parameter->subscribe_notifications == true) {
                            echo $this->Form->postLink('turn off', ['_name' => 'link-alert-subscribe', $l->private_token], [
                                'class' => 'btn btn-xs btn-danger glyphicon glyphicon-bell', 'title' => '=> Alert off',
                                'data' => ['subscribe-notifications' => "off"]]);
                        } else {
                            echo $this->Form->postLink('turn on', ['_name' => 'link-alert-subscribe', $l->private_token], [
                                'class' => 'btn btn-xs btn-success glyphicon glyphicon-bell', 'title' => '=>Alert on',
                                'data' => ['subscribe-notifications' => "on"]]);
                        }
                    }
                    if ($l->status == true) {
                        echo $this->Form->postLink('', ['_name' => 'link-disable', $l->id], [
                            'class' => 'btn btn-xs btn-warning glyphicon glyphicon-ban-circle disable-link', 'title' => '=> Disable']);
                    } else {
                        echo
                        $this->Form->postLink('', ['_name' => 'link-enable', $l->id], [
                            'class' => 'btn btn-xs btn-success glyphicon glyphicon-ok-sign enable-link', 'title' => '=> Enable']);
                    }
                    echo $this->Form->postLink('', ['_name' => 'link-delete', $l->private_token], ['confirm' => __("Are you sure you want to delete : '") . $l->title . "' ?",
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
