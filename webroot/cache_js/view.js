/**
 * downCount: Simple Countdown clock with offset
 * Author: Sonny T. <hi@sonnyt.com>, sonnyt.com
 */

(function ($) {

    $.fn.downCount = function (options, callback) {
        var settings = $.extend({
                date: null,
                offset: null
            }, options);

        // Throw error if date is not set
        if (!settings.date) {
            $.error('Date is not defined.');
        }

        // Throw error if date is set incorectly
        if (!Date.parse(settings.date)) {
            $.error('Incorrect date format, it should look like this, 12/24/2012 12:00:00.');
        }

        // Save container
        var container = this;

        /**
         * Change client's local date to match offset timezone
         * @return {Object} Fixed Date object.
         */
        var currentDate = function () {
            // get client's current date
            var date = new Date();

            // turn date to utc
            var utc = date.getTime() + (date.getTimezoneOffset() * 60000);

            // set new Date object
            var new_date = new Date(utc + (3600000*settings.offset))

            return new_date;
        };

        /**
         * Main downCount function that calculates everything
         */
        function countdown () {
            var target_date = new Date(settings.date), // set target date
                current_date = currentDate(); // get fixed current date

            // difference of dates
            var difference = target_date - current_date;

            // if difference is negative than it's pass the target date
            if (difference < 0) {
                // stop timer
                clearInterval(interval);

                if (callback && typeof callback === 'function') callback();

                return;
            }

            // basic math variables
            var _second = 1000,
                _minute = _second * 60,
                _hour = _minute * 60,
                _day = _hour * 24;

            // calculate dates
            var days = Math.floor(difference / _day),
                hours = Math.floor((difference % _day) / _hour),
                minutes = Math.floor((difference % _hour) / _minute),
                seconds = Math.floor((difference % _minute) / _second);

                // fix dates so that it will show two digets
                days = (String(days).length >= 2) ? days : '0' + days;
                hours = (String(hours).length >= 2) ? hours : '0' + hours;
                minutes = (String(minutes).length >= 2) ? minutes : '0' + minutes;
                seconds = (String(seconds).length >= 2) ? seconds : '0' + seconds;

            // based on the date change the refrence wording
            var ref_days = (days === 1) ? 'day' : 'days',
                ref_hours = (hours === 1) ? 'hour' : 'hours',
                ref_minutes = (minutes === 1) ? 'minute' : 'minutes',
                ref_seconds = (seconds === 1) ? 'second' : 'seconds';

            // set to DOM
            container.find('.days').text(days);
            container.find('.hours').text(hours);
            container.find('.minutes').text(minutes);
            container.find('.seconds').text(seconds);

            container.find('.days_ref').text(ref_days);
            container.find('.hours_ref').text(ref_hours);
            container.find('.minutes_ref').text(ref_minutes);
            container.find('.seconds_ref').text(ref_seconds);
        };
        
        // start
        var interval = setInterval(countdown, 1000);
    };

})(jQuery);
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
    if ($countDown.size()) {
        var deathDate = $('ul.countdown').attr("data-death-date");
        $('.countdown').downCount({
            date: deathDate
        });
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function () {    
    initHandlers();
});

function retrieveLinkInformation() {
    var $section = $('section#link-information');
    $section.find('.alert.alert-danger').remove()
    var savedHtml = $section.html();
    $.ajax({
            type: "GET",
            url: window.location.pathname,
            beforeSend: function() {                                                             
                $section.html('<div class="three-quarters-loader">Loading link ...</div>');
            }       
    }).success(function (html) {         
        $section.removeClass('unloaded');
        $section.html(html);
        initRemainingViews();
        initDownCount();
        init_utc_time();
    }).fail(function(error) {
        $section.html(savedHtml);
        $section.prepend('<div class="alert alert-danger col-lg-12">Problem while retrieving information</div>');
        
        //Do not reload hover event as mouse is probably still on the image place
        $('button#load-link').on('click', function () {
            var $section = $('section#link-information');
            $section.find('button').off('click');                
            retrieveLinkInformation();
        });
    });    
}

function initHandlers() {
    $('button#load-link').on('click', function () {
        var $section = $('section#link-information');
        $section.find('button').off('click');                
        retrieveLinkInformation();
    });
    $('section#link-information.unloaded img').hover(function() {        
        $('section#link-information').find('img');        
        retrieveLinkInformation();
    });
}