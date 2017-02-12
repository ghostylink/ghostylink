/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('jquery');
var noUiSlider = require('nouislider');

function update_range_color(min_life, max_life) {        

    var $min_life = $('#min_life');
    var $max_life = $('#max_life');
    var color = 'orange';
    if (min_life <= 100 / 3.0) {
        color = 'green';
    }
    else if (min_life >= 2 * 100 / 3.0) {
        color = 'red';
    }
    $min_life.val(min_life).parent().css('color', color);
    color = 'orange';
    if (max_life <= 100 / 3.0) {
        color = 'green';
    }
    else if (max_life >= 2 * 100 / 3.0) {
        color = 'red';
    }
    $max_life.val(max_life).parent().css('color', color);
}
$(function () {
    var $min_life = $('[name="min_life"]');
    var $max_life = $('[name="max_life"]');
    var $slider = $('#slider-range');
    noUiSlider.create($slider[0], {
	start: [ $min_life.val(), $max_life.val() ],
        step: 1,
	connect: true,
	range: {
           'min': 0,
           'max': 100
	}       
    });
    $slider[0].noUiSlider.on('update', function(values, handle) {                
	var value = values[handle];        
	if ( handle ) {
            $max_life.val(Math.round(value));                       
	}
        else {
            $min_life.val(Math.round(value));
        } 
        update_range_color(Math.round(values[0]), Math.round(values[1]));
    });
    $slider.find('.noUi-connect').addClass("slider-range");    
    var $deathTimeLabels = $('#death_time label');
    var $status = $('#radio label');
    $status.click(function(){
       var $this = $(this);
       $this.siblings('label').removeClass('btn-primary');
       $this.addClass('btn-primary');
    });
    $status.siblings('input[type="radio"]')
            .filter(':checked').next('label').click();
//    $("#slider-range").slider({
//        range: true,
//        min: 0,
//        max: 100,
//        values: [$('input[name=min_life]').val(), $('input[name=max_life]').val()],
//        slide: function (event, ui) {
//            update_range_color(ui.values[0], ui.values[1]);
//        }
//    });
   // $("#radio").buttonset().find('.ui-button-text').addClass('glyphicon')
    //$("#radio").buttonset().find('[for !=status-any] .ui-button-text').addClass('glyphicon-flag');
    console.log($slider.get());
    
    var min_life = Math.round($slider[0].noUiSlider.get()[0]);
    var max_life = Math.round($slider[0].noUiSlider.get()[1]);
    update_range_color(min_life, max_life);

    /* Build click event on the 'almost ghostified button' */
    $('#almost-ghostified').on('click', function () {
        $slider[0].noUiSlider.set( [$('#filters').attr("data-life-threshold"), 100]);        
        $('[name="status"]').val(1);
        update_range_color($('#filters').attr("data-life-threshold"), 100);
    });
});
