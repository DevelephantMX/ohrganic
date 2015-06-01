<?php
   /*
   Plugin Name: Factura Electrónica
   Plugin URI: http://factura.com
   Description: Plugin para conectarse a factura.com
   Version: 1.0
   Author: Paul Soberanes
   Author URI: http://neubox.com
   License: GPL2
   */

function get_factura() {

  $search = $_REQUEST["search"];
  /*



  if($invoice === null) {
      $result['type'] = "error";
      $result['message'] = "No data received";
  }
  else {
      $result['type'] = "success";
      $result['message'] = $invoice;
  }

  if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      $result = json_encode($result);
      echo $result;
  }
  else {
     header("Location: ".$_SERVER["HTTP_REFERER"]);
  }
  */

  echo "csrf: " . $_REQUEST["csrf"];
  echo "RFC: " . $_REQUEST["rfc"];
  echo "order: " . $_REQUEST["order"];
  echo "email: " . $_REQUEST["email"];
  die();

}

add_action("wp_ajax_get_factura", "get_factura");
add_action("wp_ajax_nopriv_get_factura", "get_factura");

function my_script_enqueuer() {
   wp_register_script( "facturacion_script", WP_PLUGIN_URL.'/facturacion/facturacion.js', array('jquery') );
   wp_localize_script( 'facturacion_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
   wp_enqueue_script( 'jquery' );

   wp_enqueue_script( 'facturacion_script' );

}

add_action( 'init', 'my_script_enqueuer' );
