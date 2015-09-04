<div id="left-block" class="col-lg-6">
    <?= $this->Html->image("logos/ghostylink-logo-300x250.png",
                            array('class' => 'logo hidden-xs',
                                  'alt' => 'ghostylink logo'));?>
    <p class="description col-lg-12 hidden-xs">
        Ghostylink is a temporary link manager. It will help you to share
        private information (a phone number, a password ...) without writting it
        permanently on a social network, a mail or on a chat system.
    </p>
<section class="panel panel-info col-lg-12 link-components">
    <h2 class="panel panel-heading ">Choose component to add to your link</h2>
    <div class="panel-body">
        <ul id="link-components-available">
            <li data-related-field="death_time" class="glyphicon glyphicon-time label label-primary ui-widget-header"
                data-field-js-function = "deathTimeInit"
                data-field-html="<?= htmlspecialchars($this->element("Link/Components/death_time")) ?>"> Time limit</li>
            <li data-related-field="max_views" class="glyphicon glyphicon-eye-open label label-primary ui-widget-header"
                data-field-html="<?= htmlspecialchars($this->element("Link/Components/max_views"))  ?>"> Views limit</li>
            <li data-related-field="google_captcha" class="glyphicon  glyphicon-recaptcha label label-primary ui-widget-header"
                  data-content="-"
                  data-field-html="<?= htmlspecialchars($this->element("Link/Components/google_captcha"))  ?>"> Google captcha</li>
        </ul>
    </div>
</section>
</div>
<div class="actions columns col-lg-6">
    <?php echo $this->element('Link/add'); ?>
</div>

