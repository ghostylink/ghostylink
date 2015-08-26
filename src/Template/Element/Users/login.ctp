<!-- src/Template/Users/login.ctp -->
<?= $this->Flash->render('auth') ?>
<?= $this->Form->create(null, ['url' => ['controller' => 'Users', 
                                         'action' => 'login'],                      
                               'class' => 'navbar-form navbar-right']); ?>
    <fieldset>
        <div class="form-group">
            <?= $this->Form->input('username',['class' => 'form-control',
                                               'placeholder' => 'Username',
                                               'label' => false]); ?>
        </div>
        <div class="form-group">
            <?= $this->Form->input('password',['class' => 'form-control',
                                               'placeholder' => 'Password',
                                               'label' => false]); ?>
        </div>
        <?= $this->Form->button(__('Sign in'), ['class' => 'btn btn-default']); ?>
        <?= $this->Html->link('Sign up', ['controller' => 'Users', 'action' => 'add'],
                                         ['class' => 'btn btn-success']);?>        
    </fieldset>
    
<?= $this->Form->end() ?>
<?= $this->Flash->render();?>