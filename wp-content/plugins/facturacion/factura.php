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

define( 'FACTURA__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FACTURA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( FACTURA__PLUGIN_DIR . '/factura-widget.php' );
require_once( FACTURA__PLUGIN_DIR . '/factura-plugin.php' );

//init hooks
add_action("wp_ajax_get_factura", "get_factura");
add_action("wp_ajax_nopriv_get_factura", "get_factura");
add_action( 'init', 'my_script_enqueuer' );
add_action( 'wp_print_styles', 'facturacion_styles');
add_shortcode('factura_section', 'form_creation');

function form_creation(){
  $widget = new FacturaWidget();
  $widget->form_creation();
}

function facturacion_styles() {
  wp_register_style('facturacion_styles', FACTURA__PLUGIN_URL . 'facturacion.css');
  wp_enqueue_style( 'facturacion_styles' );
}

function my_script_enqueuer() {
   wp_register_script( "facturacion_script", FACTURA__PLUGIN_URL . 'facturacion.js', array('jquery') );
   wp_localize_script( 'facturacion_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'facturacion_script' );
}



function get_factura(){

  if($_REQUEST["csrf"] == null){

    $factura_plugin = new FacturaPlugin();

    if( $_REQUEST["order"] == null || $_REQUEST["email"] == null ){
      $response = array(
        "error" => array(
          "code" =>101,
          "message" => "No se recibieron algunos datos"
        ),
        "invoice" => null
      );
      echo json_encode($response, JSON_PRETTY_PRINT);
      die;
    }

    $rfc      = $_REQUEST["rfc"];
    $order_id = $_REQUEST["order"];
    $email    = $_REQUEST["email"];

    $order = $factura_plugin->get_order_by_id($order_id);

    //validar que exista la órden
    if(!$order){
      $response = array(
        "error" => array(
          "code" =>102,
          "message" => "No existe el pedido."
        ),
        "invoice" => null
      );
      echo json_encode($response, JSON_PRETTY_PRINT);
      die;
    }

    //validar que el email coincida
    $customer = $factura_plugin->get_customer('id', $order["customer_id"]);
    if($email != $customer->user_email){
      $response = array(
        "error" => array(
          "code" =>103,
          "message" => "El email ingresado no coincide con el registrado en el pedido."
        ),
        "invoice" => null
      );
      echo json_encode($response, JSON_PRETTY_PRINT);
      die;
    }

    //validar RFC
    $invoice = $factura_plugin->get_invoice($rfc);

    $response = array(
      "error" => array(
        "code" => 200,
        "message" => "La operación se ha realizado con éxito"
      ),
      "order" => $order,
      "customer" => array(
        "ID" => $customer->ID,
        "user_login" => $customer->user_login,
        "user_nicename" => $customer->user_nicename,
        "user_email" => $customer->user_email,
        "display_name" => $customer->display_name,
        "user_status" => $customer->user_status,
        "user_registered" => $customer->user_registered
      ),
      "invoice" => $invoice,
      "data_request" => array(
        "rfc" => $rfc,
        "order_id" => $order_id,
        "customer_email" => $email
      )
    );

  }else{
    $response = array(
      "error" => array(
        "code" =>100,
        "message" => "La operación no se ha podido realizar"
      ),
      "invoice" => null
    );
  }

  echo json_encode($response, JSON_PRETTY_PRINT);
  die;
}
