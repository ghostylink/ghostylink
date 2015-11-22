<?= $this->Html->css('libs/bootstrap/bootstrap.min.css', ['block' => false]); ?>
<?= $this->Html->css("http://localhost:8765" . '/css/libs/bootstrap/bootstrap.min.css'); ?>
<h1>This is a ghostification  alert from <a href="http://www.ghostylink.org">ghostylink.org</a></h1>
    <div class="col-sm-4 hidden-xs" style="vertical-align: middle">
        <img src="http://localhost:8765/img/logos/ghostylink-logo-300x250.png" alt="Ghostylink logo"/>
    </div>
    <div class="col-sm-6 col-xs-12 alert alert-warning ">
        <?php
        $singular = "link which has reached its";
        $plurial = "links which have reached their";
        $string = "{0,plural,=0{No records found }=1{You have 1 $singular} other{You have # $plurial}}";
        echo __($string, [$links->count()]);
        ?>
        alert threshold !
    </div>
<table class="table">
    <thead>
        <th>Title</th>
        <th>Consumed life</th>
        <th>Views</th>
        <th>Death date</th>
    </thead>
<tbody>
    <?php
    foreach ($links as $l) {
        ?>
        <tr>
            <td><?= $l->title ?></td>
            <td><?= $l->life_percentage ?>% (alert at <?= $l->alert_parameter->life_threshold;?> %)</td>
            <td> <?= $l->max_views == null ? '-' : $l->views . '/' . $l->max_views?></td>
            <td> <?= $l->death_time == null ? '-' : $l->death_time ?></td>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>
<div class="alert alert-info">
    <span style="font-weight: bold">Note:</span> View counters may have increased since the mail has been sent.
    Go to <a style="text-decoration: underline;" href="http://www.ghostylink.org/me">your profile</a>
    to see the current counter.
</div>