jQuery(document).ready( function($) {

  String.prototype.capitalize = function() {
    return this.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
  };

  Number.prototype.formatMoney = function(c, d, t){
  var n = this,
    c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
  };

  $(".input-cap").on('input', function(evt) {
    var input = $(this);
  	var start = input[0].selectionStart;

  	$(this).val(function (_, val) {
  	   return val.capitalize();
  	});
  	input[0].selectionStart = input[0].selectionEnd = start;
  });

  $(".input-upper").on('input', function(evt) {
    var input = $(this);
    var start = input[0].selectionStart;
    $(this).val(function(_, val){
      return val.toUpperCase();
    });
    input[0].selectionStart = input[0].selectionEnd = start;
  });

  var order_data, customer_data, invoice_data;

  $('#f-start').click(function(){
    $('.f-welcome-container').fadeOut('fast');
    $('.steps-container').stop().fadeIn('slow');
  });

  //STEP ONE
  $('#f-step-one-form').submit(function(e){
      e.preventDefault();

      if( !validateForm($(this), 1) ) {
        return false;
      }

      $("#step-one .loader_content").show();

      form_data = $(this).serializeArray();

      data = {
        action : 'get_invoice',
        csrf   : form_data[0].value,
        rfc    : form_data[1].value,
        order  : form_data[2].value,
        email  : form_data[3].value,
      }

      $.post(myAjax.ajaxurl, data, function(response) {
        console.log(response);
        $("#step-one .loader_content").hide();

        if(response.invoice == null){
          alert(response.error.message);
          return false;
        }

        var status = response.invoice.invoice_data.status;

          // continue processing
          order_data = response.order;
          customer_data = response.invoice.invoice_data.Data;

          $('#f-step-two-form').children('#fiscal-rfc').val(response.data_request.rfc);
          $('#step-two .step-instruction').addClass(response.invoice.class).text(response.invoice.message);

          if(status == "success"){
            fillFormTwo(customer_data);
          }else{
            $('#step-two-button-edit').hide();
            enableFormTwo(true);
          }

          $('#step-one').stop().hide();
          $('#step-two').stop().fadeIn('slow');

      }, 'json');

      return false;
    });

    //STEP TWO
    $('#f-step-two-form').submit(function(e){
      e.preventDefault();

      $("#step-two .loader_content").show();

      if( !validateForm($(this), 2) ) {
        $("#step-two .loader_content").hide();
        return false;
      }

      form_data = $(this).serializeArray();

      data = {
        action        : 'create_client',
        csrf          : form_data[0].value,
        api_method    : form_data[1].value,
        uid           : form_data[2].value,
        g_nombre      : form_data[3].value,
        g_apellidos   : form_data[4].value,
        g_email       : form_data[5].value,
        f_nombre      : form_data[6].value,
        f_rfc         : form_data[7].value,
        f_calle       : form_data[8].value,
        f_exterior    : form_data[9].value,
        f_interior    : form_data[10].value,
        f_colonia     : form_data[11].value,
        f_delegacion  : form_data[12].value,
        f_municipio   : form_data[13].value,
        f_estado      : form_data[14].value,
        f_pais        : form_data[15].value,
        f_cp          : form_data[16].value,
        f_telefono    : form_data[17].value,
      }

      $.post(myAjax.ajaxurl, data, function(response) {

        $("#step-two .loader_content").hide();
        var invoice_data = response.invoice;

        if( invoice_data.status == "error" ){
          alert(invoice_data.message);
        }else{
          fillInvoiceContainer(invoice_data, order_data, customer_data);
          $('#step-two').stop().hide();
          $('#step-three').stop().fadeIn('slow');
        }
      }, 'json');
    });

    //STEP THREE
    $('#step-three-button-next').click(function(e){
      e.preventDefault();
      $("#step-three .loader_content").show();

      data = {
        action        : 'generate_invoice',
        customer_data : customer_data,
        order_data    : order_data
      }

      $.post(myAjax.ajaxurl, data, function(response){
        $("#step-three").stop().hide();
        $("#step-three .loader_content").hide();

        if(response.invoice.status == "success"){

          $('#btn-success-email').stop().show().attr('data-invoice', response.invoice.invoice_uid);
          $('#btn-success-pdf').stop().show().attr('href','https://factura.com/api/publica/invoice/'+response.invoice.invoice_uid+'/pdf');
          $('#btn-success-xml').stop().show().attr('href','https://factura.com/api/publica/invoice/'+response.invoice.invoice_uid+'/xml');
        }else{

          $("#result-msg-title").text("Ha ocurrido un error. Por favor inténtelo de nuevo.");
          $("#result-msg").html(response.invoice.message);

          $('#btn-success-email').stop().show().attr('data-invoice', response.invoice.invoice_uid);
          $('#btn-success-pdf').stop().show().attr('href','https://factura.com/api/publica/invoice/'+response.invoice.invoice_uid+'/pdf');
          $('#btn-success-xml').stop().show().attr('href','https://factura.com/api/publica/invoice/'+response.invoice.invoice_uid+'/xml');
        }

        $("#step-four").stop().fadeIn("slow");
      }, 'json');

    });

    

    $('#btn-success-email').click(function(e){
      e.preventDefault();

      $('#result-email-msg').html('Enviando factura por correo electrónico...');

      var uid = $(this).attr('data-invoice');

      data = {
        action  : 'send_invoice',
        invoice : uid,
      }

      $.post(myAjax.ajaxurl, data, function(response){
        $('#result-email-msg').addClass(response.status).html(response.message);
      }, 'json');

    });

    $('.f-back').click(function(e){
      e.preventDefault();

      var form = $(this).attr("data-f");

      clearData(form);

    });

    $('#step-two-button-edit').click(function(e){
      e.preventDefault();
      var b = $(this).attr('data-b');

      if(b == 1){
        enableFormTwo(true);
        $(this).attr('data-b', 0);
        $("#f-step-two-form #apimethod").val("update");
        $("#step-two-button-next").val("Actualizar");
      }else{
        fillFormTwo(customer_data);
        enableFormTwo(false);
        $(this).attr('data-b', 1);
        $("#f-step-two-form #apimethod").val("create");
        $("#step-two-button-next").val("Siguiente");
      }

    });

    function clearData(step){
      if(step == 2){
        $("#f-step-two-form").trigger("reset");
        $("#step-two").stop().hide();
        $("#step-one").stop().fadeIn('slow');
      }else if(step == 3){
        $("#step-three").stop().hide();
        $("#step-two").stop().fadeIn('slow');
      }
    }

    function fillInvoiceContainer(invoice_data, order_data, user_data){

      $('#invoice-id').hide();
      $('#invoice-date').hide();
      //receptor
      $('#receptor-nombre').text(invoice_data.Data.RazonSocial);
      $('#receptor-rfc').text(invoice_data.Data.RFC);
      $('#receptor-direccion').text(invoice_data.Data.Calle + ' ' + invoice_data.Data.Numero
                + ' ' + invoice_data.Data.Interior
                + ', ' + invoice_data.Data.Colonia);
      $('#receptor-direccion-zone').text(invoice_data.Data.Delegacion + ' ' + invoice_data.Data.Ciudad + ' ' + invoice_data.Data.Estado
                + ' México. ' + invoice_data.Data.CodigoPosal);
      $('#receptor-email').text(invoice_data.Data.Contacto.Email);

      //emisor
      $('#emisor-nombre').text("UNMANNED SYSTEMS S A P I DE CV");
      $('#emisor-rfc').text("USY141002JX2");
      $('#emisor-direccion').text("López Mateos 400, Ladrón de Guevara");
      $('#emisor-direccion-zone').text("Guadalajara, Jalisco, México.");

      //products
      var subtotal = 0;
      var taxes    = 0;
      var products = order_data.line_items;
      var r = new Array(), j = -1;
      for (var key=0, size=products.length; key<size; key++){

          unit_price = products[key]['price'] / 1.16;
          unit_subtotal = products[key]['quantity'] * unit_price;

          r[++j] ='<tr><td>';
          r[++j] = products[key]['name'];
          r[++j] = '</td><td>';
          r[++j] = products[key]['quantity'];
          r[++j] = '</td><td>';
          r[++j] = (unit_price).formatMoney(2, '.', ',');
          r[++j] = '</td><td>';
          r[++j] = (unit_subtotal).formatMoney(2, '.', ',');
          r[++j] = '</td></tr>';

          subtotal = Number(subtotal) + Number(unit_subtotal);
          taxes    = Number(taxes) + Number(products[key]['total_tax']);

          unit_price = 0;
      }
      $('#datails-body').html(r.join(''));

      var grand_total = Number(order_data.total);
      var total_iva = grand_total * 0.16;
      var payment_method;

      if(order_data.payment_details.method_id == "paypal"){
        payment_method = "Pago con Tarjeta";
      }else{
        payment_method = "Depósito en Cuenta";
      }

      total_iva = subtotal*0.16;
      total = Math.round(subtotal+total_iva);

      $('#invoice-pmethod').text(payment_method); //order_data.payment_details.paid (para saber si está pagado)
      $('#invoice-subtotal').text(subtotal.formatMoney(2, '.', ','));
      $('#invoice-iva').text((total_iva).formatMoney(2, '.', ','));
      $('#invoice-total').text((total).formatMoney(2, '.', ','));

    }

    function enableFormTwo(b){

      if(b == true){
        $('#general-nombre').removeAttr('readonly');
        $('#general-apellidos').removeAttr('readonly');
        $('#general-email').removeAttr('readonly');

        $('#fiscal-rfc').removeAttr('readonly');
        $('#fiscal-nombre').removeAttr('readonly');
        $('#fiscal-calle').removeAttr('readonly');
        $('#fiscal-exterior').removeAttr('readonly');
        $('#fiscal-interior').removeAttr('readonly');
        $('#fiscal-colonia').removeAttr('readonly');
        $('#fiscal-ciudad').removeAttr('readonly');
        $('#fiscal-delegacion').removeAttr('readonly');
        $('#fiscal-municipio').removeAttr('readonly');
        $('#fiscal-estado').removeAttr('readonly');
        $('#fiscal-pais').removeAttr('readonly');
        $('#fiscal-cp').removeAttr('readonly');
        $('#fiscal-telefono').removeAttr('readonly');
        $('#step-two-button-edit').val('Cancelar');
        var $labels = $("#f-step-two-form label[for]");
        $labels.css({'border-color':'#67BA2F'});

      }else{
        $('#general-nombre').attr('readonly','readonly');
        $('#general-apellidos').attr('readonly','readonly');
        $('#general-email').attr('readonly','readonly');

        $('#fiscal-rfc').attr('readonly','readonly');
        $('#fiscal-nombre').attr('readonly','readonly');
        $('#fiscal-calle').attr('readonly','readonly');
        $('#fiscal-exterior').attr('readonly','readonly');
        $('#fiscal-interior').attr('readonly','readonly');
        $('#fiscal-colonia').attr('readonly','readonly');
        $('#fiscal-ciudad').attr('readonly','readonly');
        $('#fiscal-delegacion').attr('readonly','readonly');
        $('#fiscal-municipio').attr('readonly','readonly');
        $('#fiscal-estado').attr('readonly','readonly');
        $('#fiscal-pais').attr('readonly','readonly');
        $('#fiscal-cp').attr('readonly','readonly');
        $('#fiscal-telefono').attr('readonly','readonly');
        $('#step-two-button-edit').val('Editar');
        var $labels = $("#f-step-two-form label[for]");
        $labels.css({'border-color':'#c2c2c2'});
      }
    }

    function fillFormTwo(data){
      //contacto
      $('#uid').val(data.UID);
      $('#general-nombre').val(data.Contacto.Nombre);
      $('#general-apellidos').val(data.Contacto.Apellidos);
      $('#general-email').val(data.Contacto.Email);

      $('#fiscal-rfc').val(data.RFC);
      $('#fiscal-nombre').val(data.RazonSocial);
      $('#fiscal-calle').val(data.Calle);
      $('#fiscal-exterior').val(data.Numero);
      $('#fiscal-interior').val(data.Interior);
      $('#fiscal-colonia').val(data.Colonia);
      $('#fiscal-delegacion').val(data.Delegacion);
      $('#fiscal-municipio').val(data.Ciudad);
      $('#fiscal-estado').val(data.Estado);
      $('#fiscal-pais').val("México");
      $('#fiscal-cp').val(data.CodigoPosal);
      $('#fiscal-telefono').val(data.Contacto.Telefono);

      $('#step-two [type=input]').removeAttr('readonly');
    }

    function validateForm(form, step){
      if(step == 1){

        var rfc_item   = $("#f-rfc");
        var order_item = $("#f-num-order");
        var email_item = $("#f-email");

        if(rfc_item.val().length == 0){
          $("label[for='"+rfc_item.attr('id')+"']").addClass("input_error");
          rfc_item.addClass("input_error");
        }else{
          $("label[for='"+rfc_item.attr('id')+"']").removeClass("input_error");
          rfc_item.removeClass("input_error");
        }

        if(order_item.val().length == 0){
          $("label[for='"+order_item.attr('id')+"']").addClass("input_error");
          order_item.addClass("input_error");
        }else{
          $("label[for='"+order_item.attr('id')+"']").removeClass("input_error");
          order_item.removeClass("input_error");
        }

        if(email_item.val().length == 0){
          $("label[for='"+email_item.attr('id')+"']").addClass("input_error");
          email_item.addClass("input_error");
        }else{
          $("label[for='"+email_item.attr('id')+"']").removeClass("input_error");
          email_item.removeClass("input_error");
        }

        if(rfc_item.val().length > 13 || rfc_item.val().length < 12){
          $("label[for='"+rfc_item.attr('id')+"']").addClass("input_error");
          rfc_item.addClass("input_error");
          return false;
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
          $("label[for='"+rfc_item.attr('id')+"']").removeClass("input_error");
          $("label[for='"+order_item.attr('id')+"']").removeClass("input_error");
          $("label[for='"+email_item.attr('id')+"']").removeClass("input_error");

          $('#error_msj').text('').hide();
          return true;
        }else{
          $('#f-step-one-form #error_msj').text('Por favor completa y/o corrige los datos.').show();
        }


      }else if(step == 2){

        var isValid = [];
        var chkForInvalidAmount = [];

        $('#f-step-two-form .f-input').each(function () {

          var item = $(this);

          if(item.attr('id') == "fiscal-delegacion" || item.attr('id') == "fiscal-interior" ){
            return;
          }

          if(item.val().length == 0){
            $("label[for='"+item.attr('id')+"']").addClass("input_error");
            item.addClass("input_error");
            isValid.push("false");
          }else{
            $("label[for='"+item.attr('id')+"']").removeClass("input_error");
            item.removeClass("input_error");
            isValid.push("true");
          }

        });

        var valid = $.inArray( "false", isValid );

        if(valid == -1){
          $('#f-step-two-form #error_msj').text('').hide();
          return true;
        }else{
          $('#f-step-two-form #error_msj').text('Por favor completa y/o corrige los datos.').show();
        }

      }else{

      }


      return false;
    }

    function clearVariables(){

    }

});
