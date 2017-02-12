<div id="left-block" class="col-lg-6">
    <p class="panel panel-default description col-lg-12 hidden-xs">
        Produce temporary link by adding components to your content.
        You will then be able to share it without writting it permanently to
        a social network or a chat system.
    </p>
    <?= $this->Html->image("logos/ghostylink-logo-300x250.png",
                            array('class' => 'logo hidden-xs col-lg-12',
                                  'alt' => 'ghostylink logo'));?>     
    <section class="panel panel-info col-lg-6 col-md-6 col-sm-6 col-xs-12 link-components">
    <h2 class="panel panel-heading ">Available components</h2>
        <div class="panel-body">
            <ul id="link-components-available">                
                <?= $this->Link->components(null, $user); ?>
            </ul>
        </div>
    </section>
</div>
<div class="actions columns col-lg-6 col-sm-12 col-xs-12">
    <?= $this->element('Link/add'); ?>
</div>

</div>

