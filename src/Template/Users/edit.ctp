<div class="col-lg-4 col-lg-offset-4">
    <?= $this->Form->create($user) ?>
    <fieldset id="edit-user">
        <legend><?= __('Modify my information') ?></legend>
        <div>
            <?php
            echo $this->Form->input('username', ['class' => 'form-control',
                'placeholder' => "Choose a username",
                'label' => 'Username*',
                'required' => 'true']);
            echo $this->Form->input('email', ['class' => 'form-control',
                'placeholder' => "Your email",
                'required' => 'false']);
            ?>
            <?php
            $passwordHTML =  $this->Form->input('password', ['class' => 'form-control',
                                'label' => 'Password*',
                                'placeholder' => 'Choose a password',
                                'required' => 'true']);
            $confirmPasswordHTML = $this->Form->input('confirm_password', ['class' => 'form-control',
                                'label' => 'Confirmation*',
                                'type' => "password",
                                'placeholder' => 'Confirm it',
                                'required' => 'true']);
            $hasErrors = $this->Form->isFieldError("confirm_password") || $this->Form->isFieldError("password");
            ?>
            <button id="change-pwd" class="btn btn-default center-block margin-sm"
                    data-on="<?php echo $hasErrors?'true':'false'?>"
                    data-html="<?php echo htmlspecialchars($passwordHTML) . htmlspecialchars($confirmPasswordHTML)?>">
                Change my password
            </button>
            <?php
            if ($hasErrors) {
                  echo $passwordHTML . $confirmPasswordHTML;
            }
            ?>
            <label for="default_threshold">Default life percentage threshold</label>
            <div id="slider-default_threshold"></div>
            <?=
            $this->Form->input('default_threshold', [
                'id' => 'default_threshold',
                'label' => false,
                'placeholder' => "Default link alert life threshold",
                'readonly' => true,
                'type' => 'text',
                'required' => 'false']);
            ?>
        </div>
    </fieldset>
    <div>

<?= $this->Form->button(__('Modify information'), ['class' => 'btn btn-success center-block']) ?>
    </div>
<?= $this->Form->end() ?>
    <hr/>
    <div class="centered-text">
    <?= $this->Form->postLink('Delete my account', ['_name' => 'user-delete'], [
                                        'confirm' => __("Are you sure you want to your account and ALL your link ? '"),
                                        'class' => 'btn btn-danger', 'title' => 'Delete all my links and my accout']);?>
    </div>
</div>