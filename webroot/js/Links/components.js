require("jquery");
require('bootstrap');
var noUiSlider = require('nouislider');
// Include datetime picker
// Ok this is weird but see https://github.com/xdan/datetimepicker/issues/412
DateFormatter = require('php-date-formatter')($);
require('jquery-mousewheel/jquery.mousewheel.js')($);
require('jquery-datetimepicker/jquery.datetimepicker.js')($);

/**
 * Initialize draggable and click properties on the given links components
 * @param {Jquery} $JqueryLi the li jquery element to initialize
 * @returns {void}
 */
function initLinkComponents($JqueryLi) {
    //An available component can be dragged    
    //A click on an available component selects it
    $JqueryLi.on('click',function() {
        moveLinkComponents($(this),$('ul#link-components-chosen')); 
    });
}    

/**
 * Move the given component to the given area
 * @param {Jquery} $component the li elem to move
 * @param {Jquery} $targetArea  the ul area wich will receive the component
 * @returns {void}
 */
function moveLinkComponents(component, targetArea){
        //Retrieve the closer fieldset
        console.log(component);
        var $fieldset = targetArea.parentsUntil('fieldset').eq(0);                
        //Add the html corresponding to the field        
        var $newField = $(component.attr("data-field-html"));        
        $fieldset.append($newField);
        $newField.attr("data-summary-template", component.attr("data-summary-template"));
        var toEvaluate = component.attr("data-component-name");    
        try {
            eval(toEvaluate + '()');
        }
        catch(e){
            console.log(e);
        }
        //Add a hidden field to detect the chosen components
        var nameNewField = $newField.find('input').attr("name");        
        
        //No component was here, remove the legend
        if(targetArea.children('li').not('.legend').length === 0){
            targetArea.find('li.legend').remove();
        }
        
        //Save the original component for an evenutal future delete        
        $('section.link-components').data('link-component-' + nameNewField, component.clone());         
        
        //Jquery ui put inline style (principaly positions) we do not want.
        targetArea.append(component.remove().removeAttr("style"));
        var legend = component.text();
        
        
        //Remove the available component specific class and the text
        var text = '';
        if (component.attr("data-content")) {
            text = component.attr("data-content");
        }
        //$component.text(text).removeClass('ui-widget-header').attr("title", legend);
        

        /* When the chosen component will be clicked, remove it and the corresponding
        html field */
        component.on('click', function() {
            componentsChosenClick($(this), targetArea);
        });
}

var updateSummary = function updateSummary() {    
    $('[data-category]').find(".panel-body ul li").remove();
    console.log($("ul#link-components-chosen li"));
    $("ul#link-components-chosen li").not(".legend").each(function(){        
        var $chosenComponent = $(this);
        var section = $chosenComponent.attr("data-type");
        var data = $chosenComponent.data();
        var relField = $chosenComponent.attr("data-related-field");
        console.log(relField);
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
        
    var $component = $li;      
    var fieldName = $li.attr("data-related-field");
    var classWrapper = '.link-component-field';      
    //Elements in the fieldset to remove                                
    var $toRemove = $('input[name=\'' + fieldName + '\']').parents(classWrapper);
    if ($toRemove.length === 0) {
        $toRemove = $('input[name=\'' + fieldName + '\']');
    }    
    //Mark the component as available
    $('ul#link-components-available').append($component.removeAttr("style"));
        
    initLinkComponents($component);
    
    $toRemove.remove();    
    
    //Restore legend if it was the last element
    if ($dropArea.children('li').length === 0) {        
        $dropArea.html('<li class="legend">Click on an available component to choose it</li>');
    }
}
function TimeLimit() {
//    var death_time = $('#id_death_time')
//    death_time.buttonset();
    var $deathTimeLabels = $('#death_time label');
    $deathTimeLabels.click(function(){
       var $this = $(this);
       $this.siblings('label').removeClass('btn-primary');
       $this.addClass('btn-primary');
    });
    $deathTimeLabels.siblings('input[type="radio"]')
            .filter(':checked').next('label').click();
}

function DateLimit() {       
    var dt =  jQuery('#death_date');
    dt.datetimepicker();   
}

function GhostyficationAlert() {
    var $slider = $('#slider-default_threshold');
    noUiSlider.create($slider[0], {
	start: [ $('input#default_threshold').val()],
        step: 1,
	connect: [true, false],
	range: {
           'min': 0,
           'max': 100
	}       
    });
    $slider[0].noUiSlider.on('update', function(values, handle) {                
	var value = values[handle];        	
        $('input#default_threshold').val(Math.round(value));	
    });     
}

$(function () {    
    initLinkComponents($('ul#link-components-available li'));    
});

module.exports = {
    "updateSummary":updateSummary,
    "initLinkComponents":initLinkComponents,
    "componentsChosenClick":componentsChosenClick,
    "DateLimit":DateLimit,    
    "GhostyficationAlert":GhostyficationAlert,
    "TimeLimit":TimeLimit
};
