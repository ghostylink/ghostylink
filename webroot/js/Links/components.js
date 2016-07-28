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
    //An available component can be dragged
    $JqueryLi.draggable({
        cursor: "move",
        revert: "invalid",
        // At the end of the drag the moveLinkComponents will be called
        stop: function(event, ui) {
            var $component = ui.draggable;
            moveLinkComponents($component,$('ul#link-components-chosen'));
        }
    });

    //A click on an available component selects it
    $JqueryLi.on('click',function() {
        moveLinkComponents($(this),$('ul#link-components-chosen')); 
    });

    //An available component can be dropped on the chosen components area
    $('ul#link-components-chosen').droppable({
        accept:"ul#link-components-available > li",
        drop: function(event, ui) {
            var $target = $(this);                        
            var $component = ui.draggable;            
            moveLinkComponents($component, $target);        
        }
    });
}

/**
 * Move the given component to the given area
 * @param {Jquery} $component the li elem to move
 * @param {Jquery} $targetArea  the ul area wich will receive the component
 * @returns {void}
 */
function moveLinkComponents($component, $targetArea){
        //Retrieve the closer fieldset
        console.log($component);
        var $fieldset = $targetArea.parentsUntil('fieldset').eq(0);                
        //Add the html corresponding to the field        
        var $newField = $($component.attr("data-field-html"));        
        $fieldset.append($newField);
        $newField.attr("data-summary-template", $component.attr("data-summary-template"));
        var toEvaluate = $component.attr("data-component-name");    
        try {
            eval(toEvaluate + '()');
        }
        catch(e){
            ;
        }
        //Add a hidden field to detect the chosen components
        var nameNewField = $newField.find('input').attr("name");        
        
        //No component was here, remove the legend
        if($targetArea.children('li').not('.legend').size() === 0){
            $targetArea.find('li.legend').remove();
        }
        
        //Save the original component for an evenutal future delete        
        $('section.link-components').data('link-component-' + nameNewField, $component.clone());         
        
        //Jquery ui put inline style (principaly positions) we do not want.
        $targetArea.append($component.remove().removeAttr("style"));
        var legend = $component.text();
        
        
        //Remove the available component specific class and the text
        var text = '';
        if ($component.attr("data-content")) {
            text = $component.attr("data-content");
        }
        //$component.text(text).removeClass('ui-widget-header').attr("title", legend);
        

        /* When the chosen component will be clicked, remove it and the corresponding
        html field */
        $component.on('click', function() {
            componentsChosenClick($(this), $targetArea);
        });

        // The new component is now draggable
        $component.draggable({
            cursor: "move",
            revert: "invalid"
        });
}

function updateSummary() {
    $('[data-category]').find(".panel-body ul li").remove();
    $("ul#link-components-chosen li").not(".legend").each(function(){        
        var $chosenComponent = $(this);
        var section = $chosenComponent.attr("data-type");
        var data = $chosenComponent.data();
        var relField = $chosenComponent.attr("data-related-field");
        var summaryTemplate = $chosenComponent.attr("data-summary-template");        
        var $field;        
        if (relField === "death_time") {
            $field = $("[name=" + relField + ']:checked');
        }
        else if(relField === 'alert_parameter["life_threshold"]') {            
            $field = $('[name="alert_parameter[life_threshold]"]');
        }
        else {
            $field = $('[name=' + relField + ']');
        }        
        var curValue = $field.val();        
        $('[data-category=' + section + ']')
                .find(".panel-body ul")
                .append('<li class="list-group-item">' + summaryTemplate.replace('{value}', curValue) + "</li>");
    });
    
}
function componentsChosenClick($li, $dropArea) {    
    //Retrieve the component from the saving area
    var dataName = 'link-component-' + $li.attr("data-related-field");     
    var $component = $('section.link-components').data(dataName);    
    //Retrieve the name of the field
    console.log($component.attr("data-field-html"));
    var $fieldWrapper = $($component.attr("data-field-html"));  
    console.log($fieldWrapper);
    var fieldName = $li.attr("data-related-field");
    var classWrapper = $fieldWrapper.attr("class").replace(/\s/g, ".");
    
    //Elements in the fieldset to remove                            
    var $toRemove = $('input[name=' + fieldName + ']').parents('.' + classWrapper);
    if ($toRemove.size() === 0) {
        $toRemove = $('input[name=' + fieldName + ']');
    }
    //Mark the component as available
    $('ul#link-components-available').append($component.removeAttr("style"));
        
    initLinkComponents($component);
    $li.remove();
    $toRemove.remove();
    //Remove also the hidden flag
    $('input[type=hidden][name=flag-' + fieldName + ']').remove();
    
    //Restore legend if it was the last element
    if ($dropArea.children('li').size() === 0) {        
        $dropArea.html('<li class="legend">Drop some components here</li>');
    }
}
function TimeLimit() {
    $('#id_death_time').buttonset();
}

function DateLimit() {
    $('#death_date').datetimepicker();
}

function GhostyficationAlert() {    
    $('#slider-default_threshold').slider({
        range: "max",
        min: 0,
        max: 100,
        value:$('input#default_threshold').val(),
        slide: function (event, ui) {
            $('input#default_threshold').val(ui.value);
        }
    });
    $("#slider-range-max").slider("value",$('input#default_threshold').val());    
}
initLinkComponents($('ul#link-components-available li'));


