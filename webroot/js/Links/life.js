/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
(function ($) {    
    initRemainingViews();
    $(window).resize(function() {
        $('canvas.round').remove();
        initRemainingViews();
    });
    initDownCount();
})(jQuery);
  
function initRemainingViews() {
    if ($(window).width() < 981) {        
        return;
    }
    /** Code adapted from  a grafikart tutorial 
     *  http://www.grafikart.fr/tutoriels/jquery/canvas-jauge-circulaire-317
     */
    $('meter.link-life-percentage').wrap('<div class="round" />').each(function () {        
        var $meter = $(this);        
        $meter.addClass('round');
        var $div = $meter.parent();
        var min = $meter.attr('min');
        var max = $meter.attr('max');
        var low = $meter.attr('low');
        var high = $meter.attr('high');
        var val = $meter.val();

        var ratio = ($meter.val() - min) / (max - min);
        var color;
        if (val < low) {
            color = "#008110"; //green
        }
        else if (val > high) {
            color = "#fe3f44"; //red
        }
        else {
            color = "#ffbf00";
        }

        var $circle = $('<canvas class="round" width="200px" height="200px"/>');
        var $color = $('<canvas class="round" width="200px" height="200px"/>');
        $div.append($circle);
        $div.append($color);
        var ctx = $circle[0].getContext('2d');

        // White circle with a white shadow
        ctx.beginPath();
        ctx.arc(100, 100, 85, 0, 2 * Math.PI);
        ctx.lineWidth = 20;
        ctx.strokeStyle = "#f7f7f7"
        ctx.shadowOffsetX = 2;
        ctx.shadowBlur = 5;
        ctx.shadowColor = "rgba(0,0,0,0.1)";
        ctx.stroke();
        
        // Colored circle
        var ctx = $color[0].getContext('2d');
        ctx.beginPath();
        ctx.arc(100, 100, 85, -1 / 2 * Math.PI, ratio * 2 * Math.PI - 1 / 2 * Math.PI);
        ctx.lineWidth = 20;
        ctx.strokeStyle = color;
        ctx.stroke();
        ctx.lineWidth = 10;
        ctx.font = 'italic 13pt Calibri';
        ctx.fillText($meter.text(), 20, 100);
    });
}
/**
 * Initialize a countdown based on the ul.countdown element
 * @returns {undefined}
 */
function initDownCount() {
    $countDown = $('ul.countdown');
    if ($countDown.length) {
        var deathDate = $('ul.countdown').attr("data-death-date");
        $('.countdown').downCount({
            date: deathDate
        });
    }
}