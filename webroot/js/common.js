require("jquery");

$(function(){   
    init_utc_time();    
    init_flash_message();    
});

function init_utc_time() {
    $('time.utc').each(function() {
        var $time = $(this);        
        var time = $time.attr('data-utc-time');
        var date = new Date(time);
        var gmt_date =date.toString();
        var index = gmt_date.indexOf('GMT');
        var str_date = date.toLocaleString();
        $time.text(str_date);
    });
}

function init_flash_message() {
    var $flash = $('.flash-message:not(.show-later)');
    $flash.find('a').on('click', function() {
        var $this = $(this);
        $this.parent().remove();
    });
    $flash.hide().slideDown(600).delay(5000).slideUp({
            	duration:600,
            	complete:function(){
            				$flash.remove();
            			}
            });                
}

module.exports = {
    "init_utc_time":init_utc_time
};
