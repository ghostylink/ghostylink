<div class="links form col-lg-10 col-md-9">
    <?php
    if (!isset($link)){
        echo $this->Form->create('Link', ['action' => 'add']);
    }
    else {
        echo $this->Form->create($link);
    }
    ?>
    <legend>Create your ghost</legend>
    <fieldset>
        <?php
            echo $this->Form->input('title', ['type' => 'text',
                                              'id' => 'inputTitle',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter a title",
                                              'required' => 'false']);            
            echo $this->Form->input('content', ['type' => 'text',
                                              'id' => 'inputContent',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter your private contents",
                                              'required' => 'false']);            
        ?>
    </fieldset>
    <?= $this->Form->button(__('Create the link'), ['type' => 'submit',
                                                   'class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>    
</div>
