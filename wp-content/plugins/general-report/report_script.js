jQuery(document).ready(function($){

  $('#gen-sales-report').on('click', function(e){
    e.preventDefault();

    data = {
      action    : 'get_sales_report',
      from_date : $('#from_date').val(),
      to_date   : $('#to_date').val()
    }

    $('#process-msg-one').css({'visibility':'visible'});

    $.post(myAjax.ajaxurl, data, function(response) {
      console.log(response);
      $('#process-msg-one').css({'visibility':'hidden'});
      $('#sales-reports').html(response);

    }, 'json');

  });

  $('#gen-products-report').on('click', function(e){
    e.preventDefault();

    data = {
      action : 'get_products_report'
    }

    $('#process-msg-two').css({'visibility':'visible'});

    $.post(myAjax.ajaxurl, data, function(response) {
      console.log(response);
      $('#process-msg-two').css({'visibility':'hidden'});
      $('#products-reports').html(response);

    }, 'json');

  });

  $('#gen-top-products-report').on('click', function(e){
    e.preventDefault();

    data = {
      action : 'get_top_products_report'
    }

    $('#process-msg-three').css({'visibility':'visible'});

    $.post(myAjax.ajaxurl, data, function(response) {
      console.log(response);
      $('#process-msg-three').css({'visibility':'hidden'});
      $('#products-reports').html(response);

    }, 'json');

  });

  $('#gen-sales-product-report').on('click', function(e){
    e.preventDefault();

    data = {
      action : 'get_sales_products_report',
      from_date : $('#from_date_p').val(),
      to_date   : $('#to_date_p').val()
    }

    $('#process-msg-four').css({'visibility':'visible'});

    $.post(myAjax.ajaxurl, data, function(response) {
      console.log(response);
      $('#process-msg-four').css({'visibility':'hidden'});
      $('#products-reports').html(response);

    }, 'json');

  });

});
