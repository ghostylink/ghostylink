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
            echo $this->Form->input('content', ['type' => 'textarea',
                                              'id' => 'inputContent',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter your private contents",
                                              'required' => 'false']);?>
        <label>Your components</label>
        <ul id="link-components-chosen" class="col-lg-12">                        
            <?php
                if(isset($_POST['flag-max_views'])) {
                    $htmlComponent = '<li class="glyphicon glyphicon-eye-open ' .
                                                'label label-primary" ' .
                                                'data-related-field="max_views">'
                                .   ' </li>';                                                                                            
                    echo $htmlComponent;
                    echo $this->Form->hidden("flag-max_views");
                }
                if(isset($_POST['flag-death_time'])) {
                    $htmlComponent = '<li class="glyphicon glyphicon-time ' .
                                                'label label-primary" ' .
                                                'data-related-field="death_time">'
                                .   ' </li>';                                                                                            
                    echo $htmlComponent;
                    echo $this->Form->hidden("flag-death_time");
                }
                if(!isset($_POST['flag-max_views']) && !isset($_POST['flag-death_time'])) {
                    echo '<span class="legend">Drop some components here</span>';
                }
            ?>
        </ul>    
        <?php        
        if(isset($_POST['flag-max_views'])) {
            echo $this->Form->input('max_views', ['type' => 'number',
                                              'id' => 'inputContent',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter your links life expectancy (number of views)",
                                              'required' => 'false']);
        }
        else if ($this->Form->isFieldError('max_views')){
            echo  $this->Form->error('max_views');
        }
        if(isset($_POST['flag-death_time'])) {
            echo $this->Form->input('death_time', ['type' => 'number',
                                              'id' => 'inputContent',
                                              'class' => 'form-control', 
                                              'placeholder' => "Enter your links life expectancy (number of days)",
                                              'required' => 'false']);
        }

        ?>
    </fieldset>
    <?= $this->Form->button(__('Create the link'), ['type' => 'submit',
                                                   'class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>    
</div>
