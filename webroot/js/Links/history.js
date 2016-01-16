/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 100,
        values: [$('input[name=min_life]').val(), $('input[name=max_life]').val()],
        slide: function (event, ui) {
            update_range_color(ui.values[0], ui.values[1]);
        }
    });
    $("#radio").buttonset().find('.ui-button-text').addClass('glyphicon')
    $("#radio").buttonset().find('[for !=status-any] .ui-button-text').addClass('glyphicon-flag');

    update_range_color($("#slider-range").slider("values", 0), $("#slider-range").slider("values", 1));

    /* Build click event on the 'almost ghostified button' */
    $('#almost-ghostified').on('click', function () {
        $("#slider-range").slider("option", "values", [$('#filters').attr("data-life-threshold"), 100]);
        $('[name="status"]').val(1);
        update_range_color($('#filters').attr("data-life-threshold"), 100);
    });
});
