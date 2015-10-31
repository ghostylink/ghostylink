<div class="col-lg-6">
    <h2>Why creating an account ?</h2>
    <div class="list-group">
        <a href="#" class="list-group-item list-group-item-info">
            <h4 class="list-group-item-heading">
                <span class="glyphicon glyphicon-list-alt"> Link history</span>
            </h4>
            <p class="list-group-item-text">See links
            you have already created and reactivate them if needed</p>
        </a>
        <a href="#" class="list-group-item list-group-item-warning">
            <h4 class="list-group-item-heading">
                <span class="glyphicon glyphicon-warning-sign">
                    Ghostyfication warnings (coming soon)
                </span>
            </h4>
            <p class="list-group-item-text">Be warned when one of your link
            reaches a critical percentage you defined
            </p>
        </a>
        <a href="#" class="list-group-item list-group-item-success">
            <h4 class="list-group-item-heading">
                <span class="glyphicon glyphicon-user"> Account specific encryption (coming soon)
                </span>
            </h4>
            <p class="list-group-item-text">Allow other users to create link that
                only you will be able to decrypt.</p></a>
        <a href="#" class="list-group-item list-group-item">
            <h4 class="list-group-item-heading">
                <span class="glyphicon glyphicon-euro"> It's free
                </span>
            </h4>
            <p class="list-group-item-text">No extra fees and open sources</p>
        </a>

    </div>
</div>
<div class="col-lg-4 col-lg-offset-1">
    <?= $this->Form->create($user) ?>
    <fieldset id="add-user">
        <legend><?= __('Create my account') ?></legend>
        <div>
        <?php
            echo $this->Form->input('username', ['class' => 'form-control',
                                                 'placeholder' => "Choose a username",
                                                 'label' => 'Username*',
                                                 'required' => 'true']);
            echo $this->Form->input('password', ['class' => 'form-control',
                                                 'label' => 'Password*',
                                                 'placeholder' => "Choose a password",
                                                 'required' => 'true']);
            echo $this->Form->input('email', ['class' => 'form-control',
                                              'placeholder' => "Your email",
                                              'required' => 'false']);
        ?>
        </div>
    </fieldset>
    <div>
        <?= $this->Form->button(__('Let\'s go !'),
                                ['class' => 'btn btn-success center-block']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
