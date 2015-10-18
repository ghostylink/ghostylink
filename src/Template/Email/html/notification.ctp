<?= $this->Html->css('libs/bootstrap/bootstrap.min.css', ['block' => false]); ?>
<?= $this->Html->css($this->Url->build('css/libs/bootstrap/bootstrap.min.css', true)); ?>
<div class="alert alert-info">You have links which are nearly ghostified</div>
<table class="table">
    <thead>
        <th>Title</th>
        <th>Life</th>
    </thead>
<tbody>
    <?php
    foreach ($links as $l) {
        ?>
        <tr>
            <td><?= $l->title ?></td>
            <td><?= $l->life_percentage ?></td>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>