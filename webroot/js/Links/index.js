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
            secretKey = CryptoJS.lib.WordArray.random(16).toString(CryptoJS.enc.Base64);        
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
            if($responseHTML.find('form').size() === 0) {
                //No error have been found 
                $('form[action="/add"] div.alert.alert-danger').remove();
                $('.alert.alert-danger').remove();
                $('section.generated-link').remove();
                $responseHTML.find('.link-url').first().append("#" + secretKey);
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
                    try {
                        eval($(this).attr("data-component-name") + '()');
                    }
                    catch (e) {
                        ;
                    }                    
                });
                $('ul#link-creation a').click(function (e) {
                    $(this).tab('show');        
                    updateSummary();
                    e.preventDefault();
                });                
            }
            $form.find('[name="content"]').val(noEncryptedContent);
            
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
        clipboard = new Clipboard("button.link-copy");
        clipboard.on('success', function (e) {
            var span = $('<div class="copy-instruction"><span class="label label-default">Copied !</span></div>');
            $('section.generated-link').find('span.copy-instruction').remove();
            $('section.generated-link .link-wrapper').first().append(span);            
        });
        clipboard.on('error', function (e) {
            var span = $('<div class="copy-instruction"><span class="label label-default">Press Ctrl+C to copy !</span></div>');
            $('section.generated-link').find('span.copy-instruction').remove();
            $('section.generated-link .link-wrapper').first().append(span);  
        });
    });
}

$(function () {
    initAjaxSubmission(); 
    $('ul#link-creation a').click(function (e) {
        $(this).tab('show');
        console.log("toto");
        updateSummary();
        e.preventDefault();
    });    
});


