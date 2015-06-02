<?php

require 'factura-api.php';
use FacturaApi;

class FacturaPlugin {
	private $api_host   = 'http://localhost/ohrganic/invoiceapi.php';
	private $api_key    = '785546fsd1325646498dfd8f46ds5f4ds';
	private $api_secret = '101000111011011';

	function __construct() {

	}

	public function get_invoice($rfc){

		/*
		//consultar api de factura.com
		*/
		$factura_api = new FacturaApi($this->api_host, $this->api_key, $this->api_secret);
		$invoice = $factura_api->get_invoice_api($rfc);

		return $invoice;
	}

	public function set_invoice($data, $order){

		$factura_api = new FacturaApi($this->api_host, $this->api_key, $this->api_secret);
		$invoice = $factura_api->set_invoice_api($data, $order);

		return $invoice;

	}

	public function get_order_by_id($order_id) {
		//consultar pedido en wordpress

    $order = wc_get_order( $order_id );
    $order_post = get_post( $order_id );

		if(!$order){
      return false;
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

		return $order_data;

	}

	public function get_customer($field, $value){
		$user = get_user_by( $field, $value );
		return $user->data;
	}



}
