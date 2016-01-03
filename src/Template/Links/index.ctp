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
            <?php
                echo $this->element(
                    "Link/Components/badge-time",
                    ['data' => ['data-related-field' => 'death_time',
                                       'data-field-html' => htmlspecialchars($this->element("Link/Components/death_time")),
                                       'data-field-js-function' => 'deathTimeInit']]
                );
                echo $this->element(
                    "Link/Components/badge-date",
                    ['data' => ['data-related-field' => 'death_date',
                                       'data-field-js-function' => 'deathDateInit',
                                       'data-field-html' => htmlspecialchars($this->element("Link/Components/death_date"))]]
                );
                echo $this->element(
                    "Link/Components/badge-recaptcha",
                    ['data' => ['data-related-field' => 'google_captcha',
                                       'data-field-html' => htmlspecialchars($this->element("Link/Components/google_captcha"))]]
                );
                echo $this->element(
                    "Link/Components/badge-views",
                    ['data' => ['data-related-field' => 'max_views',
                                       'data-field-html' => htmlspecialchars($this->element("Link/Components/max_views"))]]
                );
                $user = $this->request->session()->read('Auth.User');
                if ($user && $user["email_validated"] === true) {
                    echo $this->element(
                        "Link/Components/badge-alert",
                        ['data' => ['data-related-field' => 'ghostification_alert',
                                           'data-field-js-function' => 'alertComponentInit',
                                           'data-field-html' => htmlspecialchars($this->element("Link/Components/ghostification_alert"))]]
                    );
                }
                ?>
        </ul>
    </div>
</section>
</div>
<div class="actions columns col-lg-6">
    <?php echo $this->element('Link/add'); ?>
</div>

