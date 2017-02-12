require('jquery');
var noUiSlider = require('nouislider');
$(function () {
    $('button#change-pwd').on('click', function (e) {
        var $button = $(this);

        if ($button.attr('data-on') === 'true') {
            console.log($button.siblings('div.input.password, div.input.confirm_password'));
            $button.siblings('div.input.password, div.input.confirm_password').remove();
            $button.attr('data-on', 'false');
        }
        else {
            $button.after($button.attr('data-html'));
            $button.attr('data-on', 'true');
        }

        e.preventDefault();
    });
    
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
});        
