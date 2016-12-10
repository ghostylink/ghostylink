/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Encryptor = require('../libs/encryptor.js');

$(function () {
    initHandlers();
    var encryptor = new Encryptor();
    // Decrypt           
    var url = window.location.href;
    var index = url.indexOf('#');
    if (index) {
        var key = url.substring(index + 1, url.length);
        var cryptedContent = $('.link-content p').text();
        var content = encryptor.decrypt({"content":cryptedContent, "key":key});
        $('.link-content p').text(content);    
    }
});

function retrieveLinkInformation(requestType, requestData) {
    var $section = $('section#link-information');
    $section.find('.alert.alert-danger').remove()
    var savedHtml = $section.html();
    $.ajax({
        type: requestType,
        url: window.location.pathname,
        data: requestData,
        beforeSend: function () {
            $section.html('<div class="three-quarters-loader">Loading link ...</div>');
        }
    }).done(function (html) {
        $section.removeClass('unloaded');
        $section.html(html);
        initRemainingViews();
        initDownCount();
        init_utc_time();
        // Decrypt
        if (window.location.href.indexOf('#') > 0)  {
            decryptMessage();
        }
    }).fail(function (error) {
        $section.html(savedHtml);
        $section.append('<div class="alert alert-danger col-lg-12">Problem while retrieving information</div>');

        //Do not reload hover event as mouse is probably still on the image place
        $('button#load-link').on('click', function () {
            var $section = $('section#link-information');
            $section.find('button').off('click');
            retrieveLinkInformation();
        });
    });
}

function initHandlers() {
    $('button#load-link-max_views').on('click', function () {
        var $section = $('section#link-information');
        $section.find('button').off('click');
        retrieveLinkInformation("GET", {});
    });
    $('button#load-link-captcha').on('click', function (e) {
        var $section = $('section#link-information');
        $section.find('button').off('click');
        var values = {};
        $.each($('#form-captcha').serializeArray(), function (i, field) {
            values[field.name] = field.value;
        });
        console.log(values);
        retrieveLinkInformation("POST", values);
        e.preventDefault();
    }),
    $('section#link-information.unloaded img').hover(function () {
        $('section#link-information').find('img');
        retrieveLinkInformation();
    });
}

var beforeunload = function(e) {
  var $flash = $('.flash-message.link-life-alert');
  if ($("meter.link-life-percentage").val() > 95) {
    $flash.show();
    var confirmationMessage = "lol";
    window.onfocus = function() {
        var $flash = $('.flash-message.link-life-alert');
        $flash.hide();
    };    
    e.returnValue = confirmationMessage;     // Gecko, Trident, Chrome 34+
    return confirmationMessage;              // Gecko, WebKit, Chrome <34
  }
}
window.addEventListener("beforeunload", beforeunload);
//window.removeEventListener('beforeunload', beforeunload);

