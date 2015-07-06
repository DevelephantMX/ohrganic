<?php

function get_factura() {

  if($_REQUEST["csrf"] == null){

    $rfc      = $_REQUEST["rfc"];
    $order_id = $_REQUEST["order"];
    $email    = $_REQUEST["email"];

    //consultar api metodo de busqueda
    $order = wc_get_order( $order_id );
    $order_post = get_post( $order_id );

    if(!$order){
      echo "Order doesn't exists";
      die;
    }

    $order_data = array(
      'id'                        => $order->id,
      'order_number'              => $order->get_order_number(),
      'created_at'                => $order_post->post_date_gmt,
      'updated_at'                => $order_post->post_modified_gmt,
      'completed_at'              => $order->completed_date,
      'status'                    => $order->get_status(),
      'currency'                  => $order->order_currency,
      'total'                     => wc_format_decimal( $order->get_total(), 2 ),
      'total_line_items_quantity' => $order->get_item_count(),
      'total_tax'                 => wc_format_decimal( $order->get_total_tax(), 2 ),
      'total_shipping'            => wc_format_decimal( $order->get_total_shipping(), 2 ),
      'cart_tax'                  => wc_format_decimal( $order->get_cart_tax(), 2 ),
      'shipping_tax'              => wc_format_decimal( $order->get_shipping_tax(), 2 ),
      'total_discount'            => wc_format_decimal( $order->get_total_discount(), 2 ),
      'cart_discount'             => wc_format_decimal( $order->get_cart_discount(), 2 ),
      'order_discount'            => wc_format_decimal( $order->get_order_discount(), 2 ),
      'shipping_methods'          => $order->get_shipping_method(),
      'payment_details' => array(
        'method_id'    => $order->payment_method,
        'method_title' => $order->payment_method_title,
        'paid'         => isset( $order->paid_date ),
      ),
      'billing_address' => array(
        'first_name' => $order->billing_first_name,
        'last_name'  => $order->billing_last_name,
        'company'    => $order->billing_company,
        'address_1'  => $order->billing_address_1,
        'address_2'  => $order->billing_address_2,
        'city'       => $order->billing_city,
        'state'      => $order->billing_state,
        'postcode'   => $order->billing_postcode,
        'country'    => $order->billing_country,
        'email'      => $order->billing_email,
        'phone'      => $order->billing_phone,
      ),
      'shipping_address' => array(
        'first_name' => $order->shipping_first_name,
        'last_name'  => $order->shipping_last_name,
        'company'    => $order->shipping_company,
        'address_1'  => $order->shipping_address_1,
        'address_2'  => $order->shipping_address_2,
        'city'       => $order->shipping_city,
        'state'      => $order->shipping_state,
        'postcode'   => $order->shipping_postcode,
        'country'    => $order->shipping_country,
      ),
      'note'                      => $order->customer_note,
      'customer_ip'               => $order->customer_ip_address,
      'customer_user_agent'       => $order->customer_user_agent,
      'customer_id'               => $order->customer_user,
      'view_order_url'            => $order->get_view_order_url(),
      'line_items'                => array(),
      'shipping_lines'            => array(),
      'tax_lines'                 => array(),
      'fee_lines'                 => array(),
      'coupon_lines'              => array(),
    );

    // add line items
    foreach( $order->get_items() as $item_id => $item ) {
      $product = $order->get_product_from_item( $item );

      $order_data['line_items'][] = array(
        'id'         => $item_id,
        'subtotal'   => wc_format_decimal( $order->get_line_subtotal( $item ), 2 ),
        'total'      => wc_format_decimal( $order->get_line_total( $item ), 2 ),
        'total_tax'  => wc_format_decimal( $order->get_line_tax( $item ), 2 ),
        'price'      => wc_format_decimal( $order->get_item_total( $item ), 2 ),
        'quantity'   => (int) $item['qty'],
        'tax_class'  => ( ! empty( $item['tax_class'] ) ) ? $item['tax_class'] : null,
        'name'       => $item['name'],
        'product_id' => ( isset( $product->variation_id ) ) ? $product->variation_id : $product->id,
        'sku'        => is_object( $product ) ? $product->get_sku() : null,
      );
    }

    // add shipping
    foreach ( $order->get_shipping_methods() as $shipping_item_id => $shipping_item ) {

      $order_data['shipping_lines'][] = array(
        'id'           => $shipping_item_id,
        'method_id'    => $shipping_item['method_id'],
        'method_title' => $shipping_item['name'],
        'total'        => wc_format_decimal( $shipping_item['cost'], 2 ),
      );
    }

    // add taxes
    foreach ( $order->get_tax_totals() as $tax_code => $tax ) {

      $order_data['tax_lines'][] = array(
        'code'     => $tax_code,
        'title'    => $tax->label,
        'total'    => wc_format_decimal( $tax->amount, 2 ),
        'compound' => (bool) $tax->is_compound,
      );
    }

    // add fees
    foreach ( $order->get_fees() as $fee_item_id => $fee_item ) {

      $order_data['fee_lines'][] = array(
        'id'        => $fee_item_id,
        'title'     => $fee_item['name'],
        'tax_class' => ( ! empty( $fee_item['tax_class'] ) ) ? $fee_item['tax_class'] : null,
        'total'     => wc_format_decimal( $order->get_line_total( $fee_item ), 2 ),
        'total_tax' => wc_format_decimal( $order->get_line_tax( $fee_item ), 2 ),
      );
    }

    // add coupons
    foreach ( $order->get_items( 'coupon' ) as $coupon_item_id => $coupon_item ) {

      $order_data['coupon_lines'][] = array(
        'id'     => $coupon_item_id,
        'code'   => $coupon_item['name'],
        'amount' => wc_format_decimal( $coupon_item['discount_amount'], 2 ),
      );
    }

    echo json_encode($order_data, JSON_PRETTY_PRINT);die;

    $invoice = array(
      "rfc" => $rfc,
      "order" => array(
          "num" => $order,
          "concepts" => array(
              0 => "producto uno",
              1 => "producto dos",
              2 => "producto tres",
              3 => "producto cuatro",
          ),
          "date" => "30/05/2015"
      ),
      "customer" => array(
          "id" => 78545412,
          "name" => "John Doe",
          "email" => $email,
          "shipping" => array(
              "street" => "Sol",
              "city" => "Mazatlan",
              "statue" => "Sinaloa",
              "country" => "México"
          )
      )
    );

    $result = array(
      "error" => array(
        "code" => 200,
        "message" => "La operación se ha realizado con éxito"
      ),
      "invoice" => $invoice
    );

    echo json_encode($result, JSON_PRETTY_PRINT);

  }else{
    $result['type'] = "error";
    $result['message'] = "No se puede realizar la operación";
    echo json_encode($result, JSON_PRETTY_PRINT);
  }

  /*
  echo "csrf: " . $_REQUEST["csrf"];
  echo "RFC: " . $_REQUEST["rfc"];
  echo "order: " . $_REQUEST["order"];
  echo "email: " . $_REQUEST["email"];
  */
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

function  facturacion_styles() {

  wp_register_style('facturacion_styles', WP_PLUGIN_URL . '/facturacion/facturacion.css');
  wp_enqueue_style( 'facturacion_styles' );

}
add_action( 'wp_print_styles', 'facturacion_styles');
