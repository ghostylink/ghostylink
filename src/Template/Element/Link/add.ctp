<div class="links form large-10 medium-9 columns">
    <?= $this->Form->create('Link', ['action' => 'add']); ?>
    <fieldset>
        <?php
            echo $this->Form->input('title', ['type' => 'text',
                                              'id' => 'inputTitle',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter a title"]);
            echo $this->Form->input('content', ['type' => 'text',
                                              'id' => 'inputContent',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter your private contents"]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Create the link', ['type' => 'submit',
                                                   'class' => 'btn btn-default'])) ?>
    <?= $this->Form->end() ?>
</div>
