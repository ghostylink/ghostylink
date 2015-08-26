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
            <button id="change-pwd" class="btn btn-default center-block margin-sm"
                    data-on="false"
                    data-html="<?= htmlspecialchars($this->Form->input('password', ['class' => 'form-control', 
                                                                                    'label' => 'Password*',
                                                                                    'value' => '',
                                                                                    'placeholder' => 'Choose a password',
                                                                                    'required' => 'true'])); ?>">
                Change my password
            </button>            
        </div>
    </fieldset>
    <div>
        <?= $this->Form->button(__('Modify information'), 
                                ['class' => 'btn btn-success center-block']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>