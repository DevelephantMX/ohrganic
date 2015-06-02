jQuery(document).ready( function($) {

  var order_data, customer_data, invoice_data;

  $('#f-start').click(function(){
    $('.f-welcome-container').fadeOut('fast');
    $('.steps-container').stop().fadeIn('slow');
  });

  //STEP ONE
  $('#f-step-one-form').submit(function(e){
      e.preventDefault();

      if( !validateForm(1) ) {
        return false;
      }

      $("#step-two .f-loading").show();

      form_data = $(this).serializeArray();

      data = {
        action : 'get_invoice',
        csrf   : form_data[0].value,
        rfc    : form_data[1].value,
        order  : form_data[2].value,
        email  : form_data[3].value,
      }

      $.post(myAjax.ajaxurl, data, function(response) {
        $("#step-two .f-loading").hide();

        var error = response.error;

        if( error.code != 200 ){
          alert(error.message);
        }else{
          // continue processing

          order_data = response.order;
          customer_data = response.customer;

          $('#f-step-two-form').children('#fiscal-rfc').val(response.data_request.rfc);
          $('#step-two .step-instruction').addClass(response.invoice.class).text(response.invoice.message);

          if(response.invoice.invoice_data.success == true){
            fillFormTwo(response.invoice.invoice_data);
          }else{
            $('#step-two-button-edit').hide();
            enableFormTwo(true);
          }

          $('#step-one').stop().hide();
          $('#step-two').stop().fadeIn('slow');

        }


      }, 'json');

      return false;
    });

    //STEP TWO
    $('#f-step-two-form').submit(function(e){
      e.preventDefault();

      if( !validateForm(2) ) {
        return false;
      }

      form_data = $(this).serializeArray();

      data = {
        action : 'display_invoice',
        csrf   : form_data[0].value,
        rfc    : form_data[1].value,
        nombre  : form_data[2].value,
        calle  : form_data[3].value,
        exterior  : form_data[4].value,
        interior  : form_data[5].value,
        colonia  : form_data[6].value,
        municipio  : form_data[7].value,
        estado  : form_data[8].value,
        pais  : form_data[9].value,
        cp  : form_data[10].value,
        order: order_data.id
      }

      $.post(myAjax.ajaxurl, data, function(response) {

        $("#step-two .f-loading").hide();

        var error = response.error;

        if( error.code != 200 ){
          alert(error.message);
        }else{
          // continue processing

          order_data = response.order;
          customer_data = response.customer;

          $('#f-step-two-form').children('#fiscal-rfc').val(response.data_request.rfc);
          $('#step-two .step-instruction').addClass(response.invoice.class).text(response.invoice.message);

          if(response.invoice.invoice_data.success == true){
            fillFormTwo(response.invoice.invoice_data);
          }else{
            $('#step-two-button-edit').hide();
            enableFormTwo(true);
          }

          $('#step-one').stop().hide();
          $('#step-two').stop().fadeIn('slow');

        }


      }, 'json');

    });

    $('#step-two-button-edit').click(function(e){
      e.preventDefault();
      var b = $(this).attr('data-b');

      if(b == 1){
        enableFormTwo(true);
        $(this).attr('data-b', 0);
      }else{
        enableFormTwo(false);
        $(this).attr('data-b', 1);
      }

    });

    function enableFormTwo(b){

      if(b == true){

        $('#fiscal-rfc').removeAttr('readonly');
        $('#fiscal-nombre').removeAttr('readonly');
        $('#fiscal-calle').removeAttr('readonly');
        $('#fiscal-exterior').removeAttr('readonly');
        $('#fiscal-interior').removeAttr('readonly');
        $('#fiscal-colonia').removeAttr('readonly');
        $('#fiscal-ciudad').removeAttr('readonly');
        $('#fiscal-municipio').removeAttr('readonly');
        $('#fiscal-estado').removeAttr('readonly');
        $('#fiscal-pais').removeAttr('readonly');
        $('#fiscal-cp').removeAttr('readonly');
        $('#step-two-button-edit').val('Cancelar');
      }else{
        $('#fiscal-rfc').attr('readonly','readonly');
        $('#fiscal-nombre').attr('readonly','readonly');
        $('#fiscal-calle').attr('readonly','readonly');
        $('#fiscal-exterior').attr('readonly','readonly');
        $('#fiscal-interior').attr('readonly','readonly');
        $('#fiscal-colonia').attr('readonly','readonly');
        $('#fiscal-ciudad').attr('readonly','readonly');
        $('#fiscal-municipio').attr('readonly','readonly');
        $('#fiscal-estado').attr('readonly','readonly');
        $('#fiscal-pais').attr('readonly','readonly');
        $('#fiscal-cp').attr('readonly','readonly');
        $('#step-two-button-edit').val('Editar');
      }
    }

    function fillFormTwo(data){
      console.log(data);
      $('#fiscal-rfc').val(data.customer.rfc);
      $('#fiscal-nombre').val(data.customer.razon_nombre);
      $('#fiscal-calle').val(data.customer.calle);
      $('#fiscal-exterior').val(data.customer.num_exterior);
      $('#fiscal-interior').val(data.customer.num_interior);
      $('#fiscal-colonia').val(data.customer.colonia);
      $('#fiscal-ciudad').val(data.customer.ciudad);
      $('#fiscal-municipio').val(data.customer.municipio);
      $('#fiscal-estado').val(data.customer.estado);
      $('#fiscal-pais').val(data.customer.pais);
      $('#fiscal-cp').val(data.customer.codigo_postal);

      $('#step-two [type=input]').removeAttr('readonly');
    }

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
          $('#f-step-one-form #error_msj').text('Por favor completa y/o corrige los datos.').show();
        }

      }else if(step == 2){
        var rfc_item       = $("#fiscal-rfc");
        var nombre_item    = $("#fiscal-nombre");
        var calle_item     = $("#fiscal-calle");
        var exterior_item  = $("#fiscal-exterior");
        var interior_item  = $("#fiscal-interior");
        var colonia_item   = $("#fiscal-colonia");
        var municipio_item = $("#fiscal-municipio");
        var estado_item    = $("#fiscal-estado");
        var pais_item      = $("#fiscal-pais");
        var cp_item        = $("#fiscal-cp");

        // rfc
        if(rfc_item.val().length == 0){
          rfc_item.addClass("input_error");
        }else{
          rfc_item.removeClass("input_error");
        }

        // nombre
        if(nombre_item.val().length == 0){
          nombre_item.addClass("input_error");
        }else{
          nombre_item.removeClass("input_error");
        }

        // calle
        if(calle_item.val().length == 0){
          calle_item.addClass("input_error");
        }else{
          calle_item.removeClass("input_error");
        }

        // exterior
        if(exterior_item.val().length == 0){
          exterior_item.addClass("input_error");
        }else{
          exterior_item.removeClass("input_error");
        }

        // interior
        if(interior_item.val().length == 0){
          interior_item.addClass("input_error");
        }else{
          interior_item.removeClass("input_error");
        }

        // colonia
        if(colonia_item.val().length == 0){
          colonia_item.addClass("input_error");
        }else{
          colonia_item.removeClass("input_error");
        }

        // municipio
        if(municipio_item.val().length == 0){
          municipio_item.addClass("input_error");
        }else{
          municipio_item.removeClass("input_error");
        }

        // estado
        if(estado_item.val().length == 0){
          estado_item.addClass("input_error");
        }else{
          estado_item.removeClass("input_error");
        }

        // pais
        if(pais_item.val().length == 0){
          pais_item.addClass("input_error");
        }else{
          pais_item.removeClass("input_error");
        }

        // cp
        if(cp_item.val().length == 0){
          cp_item.addClass("input_error");
        }else{
          cp_item.removeClass("input_error");
        }

        //restore all input error class
        if( rfc_item.val().length > 0 && nombre_item.val().length > 0 && calle_item.val().length > 0 &&
            exterior_item.val().length > 0 && interior_item.val().length > 0 && colonia_item.val().length > 0 &&
            municipio_item.val().length > 0 && estado_item.val().length > 0 && pais_item.val().length > 0 &&
            cp_item.val().length > 0 ){

          rfc_item.removeClass("input_error");
          nombre_item.removeClass("input_error");
          calle_item.removeClass("input_error");
          exterior_item.removeClass("input_error");
          interior_item.removeClass("input_error");
          colonia_item.removeClass("input_error");
          municipio_item.removeClass("input_error");
          estado_item.removeClass("input_error");
          pais_item.removeClass("input_error");
          cp_item.removeClass("input_error");

          $('#error_msj').text('').hide();
          return true;
        }else{
          console.log('error de formulario');
          $('#f-step-two-form #error_msj').text('Por favor completa y/o corrige los datos.').show();
        }




      }else{

      }


      return false;
    }
});
