jQuery(document).ready( function($) {

  var order_data, customer_data, invoice_data;

  $('#f-start').click(function(){
    $('.f-welcome-container').fadeOut('fast');
    $('.steps-container').stop().fadeIn('slow');
  });

  $('#f-step-one-form').submit(function(e){
      e.preventDefault();

      if( !validateForm(1) ) {
        return false;
      }

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
        $(".f-loading").hide();

        var error = response.error;

        if( error.code != 200 ){
          alert(error.message);
        }else{
          // continue processing

          console.log(response);

          order_data = response.order;
          customer_data = response.customer;

          $('#f-step-two-form').children('#fiscal-rfc').val(response.data_request.rfc);
          $('#step-two .step-instruction').addClass(response.invoice.class).text(response.invoice.message);
          $('#step-one').stop().hide();
          $('#step-two').stop().fadeIn('slow');

        }


      }, 'json');

      return false;
    });

    $('#f-step-two-form').submit(function(e){
      e.preventDefault();



    });

    function validateForm(step){
      if(step == 1){

        var rfc_item   = $("#f-rfc");
        var order_item = $("#f-num-order");
        var email_item = $("#f-email");

        if(rfc_item.val().length == 0){
          rfc_item.addClass("input_error");
        }else{
          rfc_item.removeClass("input_error");
        }

        if(order_item.val().length == 0){
          order_item.addClass("input_error");
        }else{
          order_item.removeClass("input_error");
        }

        if(email_item.val().length == 0){
          email_item.addClass("input_error");
        }else{
          email_item.removeClass("input_error");
        }

        /*
        console.log(rfc_item.val().length > 0);
        console.log(order_item.val().length > 0);
        console.log(email_item.val().length > 0);
        */

        if( rfc_item.val().length > 0 && order_item.val().length > 0 && email_item.val().length > 0 ){
          rfc_item.removeClass("input_error");
          order_item.removeClass("input_error");
          email_item.removeClass("input_error");
          $('#error_msj').text('').hide();
          return true;
        }else{
          $('#error_msj').text('Por favor completa y/o corrige los datos.').show();
        }



      }


      return false;
    }
});
