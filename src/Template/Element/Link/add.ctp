<div class="links form col-lg-10 col-md-9">
    <?php
    if (!isset($link)){
        echo $this->Form->create('Link', ['action' => 'add', 'id' => 'links-add']);
    }
    else {
        echo $this->Form->create($link);
    }
    ?>
    <fieldset>
        <legend>Create your ghost</legend>
        <?php
            echo $this->Form->input('title', ['type' => 'text',
                                              'id' => 'inputTitle',
                                              'class' => 'form-control link-add',
                                              'placeholder' => "Enter a title",
                                              'required' => 'false',
                                              'autofocus' => 'true']);
            echo $this->Form->input('content', ['type' => 'textarea',
                                              'id' => 'inputContent',
                                              'class' => 'form-control',
                                              'placeholder' => "Enter your private contents",
                                              'required' => 'false']);?>
        <label>Your components</label>
        <ul id="link-components-chosen" class="col-lg-12">
            <?php
                $components = ['max_views' => 'eye-open',
                                          'death_time' => 'time',
                                          'google_captcha' => 'recaptcha',
                                          'death_date' => 'calendar',
                                          'ghostification_alert' => 'bell'];
                $content = ['max_views' => '',
                                    'death_time' => '',
                                    'google_captcha' => '_',
                                    'death_date' => '',
                                    'ghostification_alert' => '']; // artificial content to have same height on google_captcha
                $componentsEmpty = true;
                foreach ($components as $field => $cssClass) {
                    if (isset($_POST['flag-' . $field])) {
                        $htmlComponent =  '<li class="glyphicon glyphicon-' . $cssClass . ' label label-primary" ' .
                                                           'data-related-field="' . $field . '">' . $content[$field] . '</li>';
                        echo $htmlComponent;
                        echo $this->Form->hidden("flag-$field");
                        $componentsEmpty = false;
                    }
                }
                if ($componentsEmpty) {
                    echo '<li class="legend">Drop some components here</li>';
                }
            ?>
        </ul>
        <?php
        if(isset($_POST['flag-max_views'])) {
            echo $this->element('Link/Components/max_views');
        }
        else if ($this->Form->isFieldError('max_views')){
            echo  $this->Form->error('max_views');
        }
        if(isset($_POST['flag-death_time'])) {
            echo $this->element("Link/Components/death_time");
        }
        if(isset($_POST['flag-google_captcha'])) {
            echo $this->element("Link/Components/google_captcha");
        }
         if(isset($_POST['flag-death_date'])) {
            echo $this->element("Link/Components/death_date");
        }
        if(isset($_POST['flag-ghostification_alert'])) {
            echo $this->element("Link/Components/ghostification_alert");
        }
        ?>
    </fieldset>
    <?= $this->Form->button(__('Create the link'), ['type' => 'submit',
                                                   'class' => 'col-lg-6 col-lg-offset-3 '.
                                                              'col-md-6 col-md-offset-3 '.
                                                              'col-sm-6 col-sm-offset-3 '.
                                                              'col-xs-6 col-xs-offset-3 '.
                                                              'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
