/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var request;

//javascript copy function
function copy(inElement) {
  if (inElement.createTextRange) {
    var range = inElement.createTextRange();
    if (range && BodyLoaded==1)
     range.execCommand('Copy');
  } else {
    var flashcopier = 'flashcopier';
    if(!document.getElementById(flashcopier)) {
      var divholder = document.createElement('div');
      divholder.id = flashcopier;
      document.body.appendChild(divholder);
    }
    document.getElementById(flashcopier).innerHTML = '';
    var divinfo = '<embed src="http://davidwalsh.name/demo/ZeroClipboard.swf" FlashVars="clipboard='+escape(inElement.value)+'" width="0" height="0" type="application/x-shockwave-flash"/>';
    document.getElementById(flashcopier).innerHTML = divinfo;
  }
}
function initAjaxSubmission() {
    $("form").on("submit", function (event) {

        // Abort any pending request
        if (request) {
            request.abort();
        }
        // setup some local variables
        var $form = $(this);

        // Let's select and cache all the fields
        var $inputs = $form.find("input, select, button, textarea");

        // Serialize the data in the form
        var serializedData = $form.serialize();
        console.log(serializedData);
        // Let's disable the inputs for the duration of the Ajax request.
        // Note: we disable elements AFTER the form data has been serialized.
        // Disabled form elements will not be serialized.
        $inputs.prop("disabled", true);

        // Fire off the request to /form.php
        request = $.ajax({
            url: "/ghostylink/add",
            type: "post",
            data: serializedData
        });

        // Callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR) {
            // Log a message to the console
            console.log(response);
            var $responseHTML = $(response);
            
            if($responseHTML.find('form div.error').size() === 0) {
                $('form div.error div.alert.alert-danger').remove();
                $('section.generated-link').remove();
                $('#left-block').append($responseHTML);
                initCopyButton();
            }
            else {
                $form.html($responseHTML.find('form').html());
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
});


