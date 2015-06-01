jQuery(document).ready( function($) {

  $('#f-start').click(function(){
    $('.f-welcome-container').fadeOut('fast');
    $('.steps-container').stop().fadeIn('slow');
  });

  $('#f-step-one-form').submit(function(e){
      e.preventDefault();

      var invoice = 'Banana';
      $(".f-loading").show();

      form_data = $(this).serializeArray();

      data = {
        action : 'get_factura',
        csrf   : form_data[0].value,
        rfc    : form_data[1].value,
        order  : form_data[2].value,
        email  : form_data[3].value,
      }

      $.post(myAjax.ajaxurl, data, function(response) {
        console.log(response);
      });

      return false;

      /*
      $.ajax({
         type : "post",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : {action: "get_factura", invoice : invoice},
         success: function( ) {
            console.log('09')
         }
      }).done(function() {

         console.log('ok')
      })
      */

    });

});
