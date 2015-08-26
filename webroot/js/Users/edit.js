$(function(){
   $('button#change-pwd').on('click', function(e){             
      var $button = $(this);
      
      if($button.attr('data-on') === 'true') {          
          console.log($button.siblings('div.input.password'));
          $button.siblings('div.input.password').remove();
          $button.attr('data-on','false');
      }
      else {
          $button.after($button.attr('data-html'));
          $button.attr('data-on', 'true');      
      }
      
      e.preventDefault();
   });
});


