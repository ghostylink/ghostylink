<?php
    $this->start('script');
    echo $this->Html->script("libs/jquery.downCount");
    echo $this->Html->script("Links/life");
    echo $this->Html->script("Links/view");
    $this->end(); 
    
    $this->start('css');
    echo $this->Html->css("Links/view");
    $this->end();
?> 
<?php    
if (isset($link->max_views)) {?>
    <section id="link-information" class="row unloaded">        
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $this->Html->image('logos/ghostylink-logo-300x250.png',
                                        array('class' => 'hidden-xs hidden-sm'));?>
            </div>        
            <p class="col-lg-6 col-md-6 alert alert-info">
                The link you try to access has a maximum views component. This
                page prevent sharing systems (chats/social networks) from increasing the view 
                counter when a link preview is loaded. <br/><br/>
                <span class="hidden-xs hidden-sm">
                    <span class="highlight">Move your mouse on the ghost</span> or 
                </span> 
                <span class="highlight"> click on the following button</span>
                to view the link
            </p>
        </div>
        <div class="row">
            <button id="load-link" class="btn btn-primary btn-lg">
                View link information
            </button>
        </div>
    </section>
<?php    
}
else {?>
    <section id="link-information" class="row">
        <?= $this->element('Link/information');?>
    </section>
<?php
}
?>

