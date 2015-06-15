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
require_once( FACTURA__PLUGIN_DIR . '/factura-api.php' );

//init ajax hooks
add_action("wp_ajax_get_invoice", "get_invoice");
add_action("wp_ajax_nopriv_get_invoice", "get_invoice");
add_action("wp_ajax_create_client", "create_client");
add_action("wp_ajax_nopriv_create_client", "create_client");
add_action("wp_ajax_generate_invoice", "generate_invoice");
add_action("wp_ajax_nopriv_generate_invoice", "generate_invoice");

//init hooks
add_action( 'init', 'my_script_enqueuer' );
add_action( 'wp_print_styles', 'facturacion_styles');
add_shortcode('factura_section', 'form_creation');

function form_creation(){
  $widget = new FacturaWidget();
  $widget->form_creation();

  /*
  $api_host   = 'http://factura/api/v1/clients/';
  $api_key    = 'JDJ5JDEwJG5QWWcyd0hWNExDMXByc1ltQjVEeU9QdGFxSmZ0Ni5vWFA2RXdsVDVLdml3QWF3TEs1aHA2';
  $api_secret = 'JDJ5JDEwJGgvL2xoNnlnMkRHYkRyblpleVBjZ2VVcmZITW9VQm40VHNXSGdZTlJmU3E2QjRmVFRqbVl1';

  $factura_api = new FacturaApi($api_host, $api_key, $api_secret);
  $invoice = $factura_api->get_invoice_api('ABM100930JQ2');

  echo "<pre>";
  var_dump($invoice);
  echo "</pre>";
  */
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

function generate_invoice(){
  $factura_plugin = new FacturaPlugin();

  if($_REQUEST["customer_data"] == null || $_REQUEST["order_data"] == null){
    $response = array(
      "code" =>101,
      "message" => "No se recibieron algunos datos",
      "invoice" => null
    );
    echo json_encode($response, JSON_PRETTY_PRINT);
    die;
  }

  $api_response = $factura_plugin->generate_invoice($_REQUEST["customer_data"], $_REQUEST["order_data"]);

  $response = array(
    "invoice" => $api_response
  );

  echo json_encode($response, JSON_PRETTY_PRINT);
  die;
}

function create_client(){
  if($_REQUEST["csrf"] == null){

    $factura_plugin = new FacturaPlugin();

    if( $_REQUEST["g_nombre"] == null || $_REQUEST["g_apellidos"] == null ||
    $_REQUEST["g_email"] == null || $_REQUEST["f_calle"] == null || $_REQUEST["f_colonia"] == null ||
    $_REQUEST["f_cp"] == null || $_REQUEST["f_estado"] == null || $_REQUEST["f_exterior"] == null ||
    $_REQUEST["f_municipio"] == null || $_REQUEST["f_nombre"] == null ||
    $_REQUEST["f_rfc"] == null || $_REQUEST["f_telefono"] == null ){

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

    $invoice = $factura_plugin->create_client($_REQUEST, $_REQUEST["order"]);

    $response = array(
      "invoice" => $invoice
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

function get_invoice(){

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

    if($order["status"] != "completed"){
      $response = array(
        "error" => array(
          "code" =>104,
          "message" => "El pedido no se encuentra completado. Por favor espere a que el pedido se procese."
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
        "code" => $invoice->invoice_data->status,
        "message" => $invoice->invoice_data->statusp
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
