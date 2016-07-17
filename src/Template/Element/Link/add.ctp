<div class="links form col-lg-10 col-md-9">
    <?php
    if (!isset($link)) {
        echo $this->Form->create('Link', ['id' => 'links-add']);
    } else {
        echo $this->Form->create($link);
    }
    ?>
    <fieldset>
        <legend>Create your ghost</legend>
        <!-- Nav tabs -->
        <ul id="link-creation" class="nav nav-tabs" role="tablist">
            <li role="settings" class="active">
                <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a>
            </li>
            <li role="summary">
                <a href="#summary" aria-controls="summary" role="tab" data-toggle="tab">Summary</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="settings">

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
                    'required' => 'false']);
                ?>
                <label>Your components</label>
                <ul id="link-components-chosen" class="col-lg-12">
                    <?php
                        $link = isset($link)?$link:null;
                        $this->Link->displayBadges($link);
                    ?>
                </ul>
                <?php
                    $this->Link->displayAllFields($link);
                ?>
            </div>

            <div role="tabpanel" class="tab-pane" id="summary">
                <div class="panel panel-default" data-category="link-life">
                    <div class="panel-heading">Link life</div>
                    <div class="panel-body">
                        <ul class="list-group">                                                        
                        </ul>   
                    </div>
                </div>
                <div class="panel panel-default" data-category="protection">
                    <div class="panel-heading">Protections</div>
                    <div class="panel-body">
                        <ul class="list-group">                            
                        </ul>   
                    </div>
                </div>
                <div class="panel panel-default" data-category="misc">
                    <div class="panel-heading">Misc</div>
                    <div class="panel-body">
                        <ul class="list-group">                            
                        </ul>   
                    </div>
                </div>
            </div>           
        </div>
    </fieldset>
    <?=
    $this->Form->button(__('Create the link'), ['type' => 'submit',
        'class' => 'col-lg-6 col-lg-offset-3 ' .
        'col-md-6 col-md-offset-3 ' .
        'col-sm-6 col-sm-offset-3 ' .
        'col-xs-6 col-xs-offset-3 ' .
        'btn btn-success'])
    ?>
    <?= $this->Form->end() ?>    

