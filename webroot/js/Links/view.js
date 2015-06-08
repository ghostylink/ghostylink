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
//    $('section#link-information.unloaded img').hover(function() {        
//        $('section#link-information').find('img');        
//        retrieveLinkInformation();
//    });
}

