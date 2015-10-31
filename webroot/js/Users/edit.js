$(function () {
    $('button#change-pwd').on('click', function (e) {
        var $button = $(this);

        if ($button.attr('data-on') === 'true') {
            console.log($button.siblings('div.input.password'));
            $button.siblings('div.input.password').remove();
            $button.attr('data-on', 'false');
        }
        else {
            $button.after($button.attr('data-html'));
            $button.attr('data-on', 'true');
        }

        e.preventDefault();
    });

    $('#slider-default_threshold').slider({
        range: "max",
        min: 0,
        max: 100,
        value:$('input[name="default_threshold"]').val(),
        slide: function (event, ui) {
            $('input[name="default_threshold"]').val(ui.value);
        }
    });
    $("#slider-range-max").slider("value",$('input[name="default_threshold"]').val());    
});        
