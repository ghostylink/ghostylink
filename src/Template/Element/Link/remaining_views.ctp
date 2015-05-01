<div class="meter-vertical-wrapper">
    <meter class="vertical" min="0" max="<?= _($link->max_views); ?>" 
           low="<?= _(round($link->max_views * 0.33)) ?>"
           high="<?= _(round($link->max_views * 0.66)); ?>" 
           optimum="<?= _($link->max_views); ?>"
           value="<?= _($link->remaining_views); ?>">
        <?= _($link->remaining_views); ?> views left on this link
    </meter>
    <div><?= _($link->remaining_views); ?> views left on this link</div>
</div>