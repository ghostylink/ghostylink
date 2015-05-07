/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Initialize draggable and click properties on the given links components
 * @param {Jquery} $JqueryLi the li jquery element to initialize
 * @returns {void}
 */
function initLinkComponents($JqueryLi) {
    $JqueryLi.draggable({
        cursor: "move",
        revert: "invalid"
    });
    $JqueryLi.on('click',function() {
        moveLinkComponents($(this),$('ul#link-components-chosen')); 
    });
}

/**
 * Move the given component to the given area
 * @param {Jquery} $JqueryElem the elem to move
 * @param {Jquery} $JqueryTo    the area wich will receive the component
 * @returns {void}
 */
function moveLinkComponents($JqueryElem, $JqueryTo){
        //Retrieve the closer fieldset
        var $fieldset = $JqueryTo.parent('fieldset').eq(0);
        
        //Add the html corresponding to the element
        $fieldset.append($JqueryElem.attr("data-field-html"));                
        if($JqueryTo.children('li').size() === 0){
            $JqueryTo.find('span.legend').remove();
        }
        
        //Jquery ui put inline style (principaly positions) we do not want.
        $JqueryTo.append($JqueryElem.remove().removeAttr("style"));
        var legend = $JqueryElem.text();
        
        //Save the original component for an evenutal future delete
        $JqueryElem.data('component', $JqueryElem.clone());
        $JqueryElem.text('').removeClass('ui-widget-header').attr("title", legend);
        
        /* When the chosen component will be clicked, remove it and the corresponding
        html field */
        $JqueryElem.on('click', function() {
           var $this = $(this);
           var $component = $this.data('component');
           var $fieldWrapper = $($component.attr("data-field-html"));
           var $field = $fieldWrapper.find('input');
           var fieldName = $field.attr('name');           
           var classWrapper = $fieldWrapper.attr("class").replace(/\s/g, ".");                     
           var $toRemove = $('input[name=' + fieldName + ']').parents('.' + classWrapper);
           $('ul#link-components-available').append($component);
           initLinkComponents($component);           
           $this.remove();
           $toRemove.remove();           
           if($JqueryTo.children().size() === 0) {
               $JqueryTo.html('<span class="legend">Drop some components here</span>');
           }
        });
}

initLinkComponents($('ul#link-components-available li'));


$('ul#link-components-chosen').droppable({
    accept:"ul#link-components-available > li",
    drop: function(event, ui) {
        $target = $(this);                        
        $component = ui.draggable;
        moveLinkComponents($component, $target);        
    }
});