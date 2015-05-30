jQuery(document).ready( function() {

  jQuery('#f-start').click(function(){
    jQuery('.f-welcome-container').fadeOut('fast')
    jQuery('.steps-container').stop().fadeIn('slow')
  })

  jQuery('#step-one-button-next').click(function(e){
      e.preventDefault()

      var invoice = 'Banana';
      jQuery(".f-loading").show()

      jQuery.ajax({
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


    })

})
