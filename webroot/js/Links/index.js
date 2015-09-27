/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var request;

function initAjaxSubmission() {
    $("form#links-add").on("submit", function (event) {        
        // Abort any pending request
        if (request) {
            request.abort();
        }
        // setup some local variables
        var $form = $(this);

        // Let's select and cache all the fields
        var $inputs = $form.find("input, select, button, textarea");
        $form.append('<input type="hidden" name="timezone-offset" value="' + new Date().getTimezoneOffset() + '"/>');                
        
        // Encrypt content        
        var noEncryptedContent = $('[name="content"]').val();
        var secretKey ;
        var ciphertext;
        if (noEncryptedContent !== "") {
            secretKey = CryptoJS.lib.WordArray.random(32).toString(CryptoJS.enc.Base64);        
            ciphertext = CryptoJS.AES.encrypt(noEncryptedContent, secretKey.toString());        
            $('[name="content"]').val(ciphertext.toString());        
        }
        // Serialize the data in the form
        var serializedData = $form.serialize();        
        // Let's disable the inputs for the duration of the Ajax request.
        // Note: we disable elements AFTER the form data has been serialized.
        // Disabled form elements will not be serialized.
        $inputs.prop("disabled", true);

        // Fire off the request to /form.php
        request = $.ajax({
            url: "add",
            type: "post",
            data: serializedData
        });

        // Callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR) {
            // Log a message to the console            
            var $responseHTML = $(response);
            //Restore non encrypted message
            $form.find('[name="content"]').val(noEncryptedContent);
            if($responseHTML.find('form').size() === 0) {
                //No error have been found 
                $('form[action="/add"] div.alert.alert-danger').remove();
                $('section.generated-link').remove();
                $responseHTML.find('.link-url').append("#" + secretKey);
                $('#main-content').append($responseHTML);
                initCopyButton();
            }
            else {
                //At least one error, rebind events on components
                $form.html($responseHTML.find('form').html());                
                $form.find("ul#link-components-chosen li").each(function() {
                    $(this).on('click', function() {
                        componentsChosenClick($(this),$('ul#link-components-chosen'));
                    });
                    $('#id_death_time').buttonset();
                    $('#death_date').datetimepicker();
                });             
            }
            
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown) {
            // Log the error to the console
            console.error(
                    "The following error occurred: " +
                    textStatus, errorThrown
                    );
        });

        // Callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
            // Reenable the inputs
            $inputs.prop("disabled", false);
        });

        // Prevent default posting of form
        event.preventDefault();
    });
}

function initCopyButton() {
    $('button.link-copy').on("click", function () {
        var doc = document;
        var text = doc.getElementById('link-url');        
        if (doc.body.createTextRange) { // ms
            var range = doc.body.createTextRange();            
            range.moveToElementText(text);
            range.select();            
        } else if (window.getSelection) { // moz, opera, webkit
            var selection = window.getSelection();
            var range = doc.createRange();
            range.selectNodeContents(text);
            selection.removeAllRanges();
            selection.addRange(range);
        }
        var span = $('<span class="copy-instruction label label-default">Press Ctrl-C to copy link</span>');
        $('section.generated-link').find('span.copy-instruction').remove();
        $('section.generated-link').prepend(span);
    });
}

$(function () {
    initAjaxSubmission(); 
    console.log($("form#links-add"));
});


