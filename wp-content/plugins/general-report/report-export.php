<?php

class ReportExport {

    function __construct() {

  	}

    public function create_sales_products_csv($filter_dates, $orders_status = null){
        ini_set('memory_limit', '256M');

        $orders = $this->get_orders($filter_dates, $orders_status);

        $entries = array();
        $headers = array("Mes","Fecha","No. pedido","Cantidad","Productos (ID - nombre)","Status pedido","Total");

        array_push($entries, $headers);

        foreach($orders as $order){

          $order_month = intval(date("m", strtotime($order["created_at"])));
          $products = array();

          foreach($order["line_items"] as $product){
              $product_item = $product["id"] . " - " .$product["name"];

              $line = array(
                  "order_month"    => $this->get_month_string($order_month),
                  "order_date"     => date("d-m-Y", strtotime($order["created_at"])),
                  "order_no"       => $order["order_number"],
                  "items_count"    => $product["quantity"],
                  "items_list"     => $product_item,
                  "status"         => $order["status"],
                  "total"          => $product["total"],
              );

              array_push($entries, $line);
          }
        }

        if(count($entries) > 1){
            $plugin_dir = WP_PLUGIN_DIR .'/general-report/products-reports';
            //$filename = $plugin_dir . '/reporte-ventas-' . date("d-m-y-h-i-s") . '.csv';
            $filename = $plugin_dir . '/reporte-ventas-productos.csv';

            // This opens up the output buffer as a "file"
            //$fp = fopen('php://output', 'w');
            //$fp = fopen( 'php://temp/maxmemory:'. (12*1024*1024) , 'r+' );
            $fp = fopen ($filename, "w");

            foreach ( $entries as $row ) {
            	fputcsv( $fp, $row );
            }

            // be kind, rewind
            rewind( $fp );

            // cerrar archivo
            fclose( $fp );

            // cabeceras HTTP:
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename='.$filename );
            header('Content-Length: '. strlen($output) );


            // enviar archivo
            // return $filename;
            $dir_open = opendir(WP_PLUGIN_DIR .'/general-report/products-reports');
            $links = array();
            $style="style='background: url(".plugin_dir_url( __FILE__ )."products-icon.png) left center no-repeat;text-decoration:none;padding-left: 20px;'";

            while(false !== ($filename = readdir($dir_open))){
                if($filename != "." && $filename != ".."){
                    $link = "<a href='".plugin_dir_url( __FILE__ )."products-reports/" .$filename . "'
                    $style
                    >". $filename ." </a><br />";
                    array_push($links, $link);
                }
            }

            closedir($dir_open);
            return $links;
        }
        return "<h3>Ha ocurrido un error. Intenta de nuevo.</h3>";
    }


    public function create_top_products_csv(){
      set_time_limit(0);
      $flag = 'top_selling';
      $products = $this->get_products($flag);

      $entries = array();
      $headers = array('Código','Stock','Count sales','Producto');
      array_push($entries, $headers);
      foreach($products as $product){

        $line = array(
          'product_id'   => $product['product_id'],
          'stock_qty'    => intval($product['product_stock']),
          'count_sales'  => intval(get_post_meta($product['product_id'], 'total_sales', true )),
          'product_name' => $product['product_data']->post_title,
        );

        array_push($entries, $line);

      }
      if($entries){
          $plugin_dir = WP_PLUGIN_DIR .'/general-report/products-reports';
          //$filename = $plugin_dir . '/reporte-ventas-' . date("d-m-y-h-i-s") . '.csv';
          $filename = $plugin_dir . '/reporte-productos-mas-vendidos.csv';

          // This opens up the output buffer as a "file"
          //$fp = fopen('php://output', 'w');
          //$fp = fopen( 'php://temp/maxmemory:'. (12*1024*1024) , 'r+' );
          $fp = fopen ($filename, "w");

          foreach ( $entries as $row ) {
          	fputcsv( $fp, $row );
          }

          // be kind, rewind
          rewind( $fp );

          // cerrar archivo
          fclose( $fp );

          // cabeceras HTTP:
          header('Content-Type: text/csv; charset=utf-8');
          header('Content-Disposition: attachment; filename='.$filename );
          header('Content-Length: '. strlen($output) );


          // enviar archivo
          // return $filename;
          $dir_open = opendir(WP_PLUGIN_DIR .'/general-report/products-reports');
          $links = array();
          $style="style='background: url(".plugin_dir_url( __FILE__ )."products-icon.png) left center no-repeat;text-decoration:none;padding-left: 20px;'";

          while(false !== ($filename = readdir($dir_open))){
              if($filename != "." && $filename != ".."){
                  $link = "<a href='".plugin_dir_url( __FILE__ )."products-reports/" .$filename . "'
                  $style
                  >". $filename ." </a><br />";
                  array_push($links, $link);
              }
          }

          closedir($dir_open);
          return $links;

      }
      return "<h3>Ha ocurrido un error. Intenta de nuevo.</h3>";
    }

    public function create_products_csv(){
      set_time_limit(0);
      $products = $this->get_products();

      $entries = array();
      $headers = array('Código','Stock','Producto');
      array_push($entries, $headers);
      foreach($products as $product){

        $line = array(
          'product_id'   => $product['product_id'],
          'stock_qty'    => intval($product['product_stock']),
          'product_name' => $product['product_data']->post_title,
        );

        array_push($entries, $line);

      }
      if($entries){
          $plugin_dir = WP_PLUGIN_DIR .'/general-report/products-reports';
          //$filename = $plugin_dir . '/reporte-ventas-' . date("d-m-y-h-i-s") . '.csv';
          $filename = $plugin_dir . '/reporte-productos.csv';

          // This opens up the output buffer as a "file"
          //$fp = fopen('php://output', 'w');
          //$fp = fopen( 'php://temp/maxmemory:'. (12*1024*1024) , 'r+' );
          $fp = fopen ($filename, "w");

          foreach ( $entries as $row ) {
          	fputcsv( $fp, $row );
          }

          // be kind, rewind
          rewind( $fp );

          // cerrar archivo
          fclose( $fp );

          // cabeceras HTTP:
          header('Content-Type: text/csv; charset=utf-8');
          header('Content-Disposition: attachment; filename='.$filename );
          header('Content-Length: '. strlen($output) );


          // enviar archivo
          // return $filename;
          $dir_open = opendir(WP_PLUGIN_DIR .'/general-report/products-reports');
          $links = array();
          $style="style='background: url(".plugin_dir_url( __FILE__ )."products-icon.png) left center no-repeat;text-decoration:none;padding-left: 20px;'";

          while(false !== ($filename = readdir($dir_open))){
              if($filename != "." && $filename != ".."){
                  $link = "<a href='".plugin_dir_url( __FILE__ )."products-reports/" .$filename . "'
                  $style
                  >". $filename ." </a><br />";
                  array_push($links, $link);
              }
          }

          closedir($dir_open);
          return $links;

      }
      return "<h3>Ha ocurrido un error. Intenta de nuevo.</h3>";
    }

    public function create_sales_csv($filter_dates, $orders_status = null){
      ini_set('memory_limit', '256M');
      $orders = $this->get_orders($filter_dates, $orders_status);

      $entries = array();

      $headers = array("Mes","Fecha","No. pedido","Cantidad","Productos (ID - nombre)","Status pedido","Email cliente","Total");
      array_push($entries, $headers);

      foreach($orders as $order){

        $order_month = intval(date("m", strtotime($order["created_at"])));
        $products = array();

        foreach($order["line_items"] as $product){
          array_push($products, $product["id"] . " - " .$product["name"]);
        }

        $line = array(
          "order_month"    => $this->get_month_string($order_month),
          "order_date"     => date("d-m-Y", strtotime($order["created_at"])),
          "order_no"       => $order["order_number"],
          "items_count"    => count($order["line_items"]),
          "items_list"     => implode(" / ",$products),
          "status"         => $order["status"],
          "customer_email" => $order["billing_address"]["email"],
          "total"          => $order["total"],
        );

        array_push($entries, $line);
      }

      if($entries){

          $plugin_dir = WP_PLUGIN_DIR .'/general-report/sales-reports';
          //$filename = $plugin_dir . '/reporte-ventas-' . date("d-m-y-h-i-s") . '.csv';
          $filename = $plugin_dir . '/reporte-ventas.csv';

          // This opens up the output buffer as a "file"
          //$fp = fopen('php://output', 'w');
          //$fp = fopen( 'php://temp/maxmemory:'. (12*1024*1024) , 'r+' );
          $fp = fopen ($filename, "w");

          foreach ( $entries as $row ) {
          	fputcsv( $fp, $row );
          }

          // be kind, rewind
          rewind( $fp );

          // cerrar archivo
          fclose( $fp );

          // cabeceras HTTP:
          header('Content-Type: text/csv; charset=utf-8');
          header('Content-Disposition: attachment; filename='.$filename );

          // enviar archivo
          //return $filename;
          $dir_open = opendir(WP_PLUGIN_DIR .'/general-report/sales-reports');
          $links = array();
          $style="style='background: url(".plugin_dir_url( __FILE__ )."sales-icon.png) left center no-repeat;text-decoration:none;padding-left: 20px;'";

          while(false !== ($filename = readdir($dir_open))){
              if($filename != "." && $filename != ".."){
                  $link = "<a href='".plugin_dir_url( __FILE__ )."sales-reports/" .$filename . "'
                  $style
                  >". $filename ." </a><br />";
                  array_push($links, $link);
              }
          }

          closedir($dir_open);
          return $links;

      }
      return "<h3>Ha ocurrido un error. Intenta de nuevo.</h3>";

    }


    public function top_selling_args(){

        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_key'       => 'total_sales',
            'orderby'        => 'meta_value_num',
        );

        return $args;
    }

    /**
     * Return all products in the system.
     *
     * @param String $flag
     * @return Array
     */
    public function get_products($flag = null){

      if($flag == 'top_selling'){
          $args = $this->top_selling_args();
      }else{
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );

      }
      $posts = get_posts($args);
      $products_collection = array();

      foreach($posts as $post){
        $product = $this->get_product_by_id($post->ID);

        if($product['product_type'] == 'variable'){

          $variations = $product['variations'];
          foreach($variations as $variation){

            $child_data['post_title'] = $variation['parent_name'] . ' - ' . $variation['attributes_val'];

            $child_product['product_id']    = $variation['variation_id'];
            $child_product['product_data']  = (object) $child_data;
            $child_product['product_stock'] = $variation['availability_stock'];

            array_push($products_collection, $child_product);
          }

        }
        array_push($products_collection, $product);

      }

      return $products_collection;

    }

    /**
     * Return order by id
     *
     * @param Int $id
     * @return Array
     */
    public function get_product_by_id($id){
      $product = wc_get_product($id);

      $product_data = array(
        'product_id'     => $product->id,
        'product_data'   => $product->post,
        'product_type'   => $product->product_type,
        //'variations'     => $product->get_available_variations(),
        'variations' => null,
      );

      if($product->product_type == 'variable'){

        /*
        $variations = $product->get_variation_attributes();

        $childs = array();
        foreach($variations as $variation){

          array_push($childs, $variation);

        }

        $availability = $product->get_available_variations();

        foreach($availability as $stock){
          array_push($childs, $stock);
        }

        */

        //$product_data['variations']['attributes'] = $childs;
        $product_data['variations'] = $product->get_available_variations();

      }

      return $product_data;

    }

    /**
     * Return orders by status. Default all statuses.
     *
     * @param String $status
     * @return Array
     */
    public function get_orders($filter_dates, $status = null){

        $terms = ($status == null) ? $this->get_order_statuses() : $status;

        $args = array(
            'post_type' => 'shop_order',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'shop_order_status',
                    'field' => 'slug',
                    'terms' => array( $terms )
                )
            ),
            'date_query' => array(
            		'after' => array(
              			'year'  => $filter_dates['after']['year'],
              			'month' => $filter_dates['after']['month'],
              			'day'   => $filter_dates['after']['day']
            		),
                'before' => array(
              			'year'  => $filter_dates['before']['year'],
              			'month' => $filter_dates['before']['month'],
              			'day'   => $filter_dates['before']['day']
            		),
            		'inclusive' => false,
          	),
        );

        $posts = get_posts($args);
        $orders_collection = array();

        foreach($posts as $post){
          $order = $this->get_order_by_id($post->ID);

          array_push($orders_collection, $order);
        }

        return $orders_collection;

    }

    /**
     * Get all order statuses
     *
     * @return array
     */
    public function get_order_statuses(){

      $statuses = wc_get_order_statuses();
      return "'" . implode("','", $statuses) . "'";
    }

    /**
     * Get all products types
     *
     * @return array
     */
    public function get_product_types(){
      $types = wc_get_product_types();
      return "'" . implode("','", $types) . "'";
    }

    /**
     * Return the orders count of a specific order status. Completed default.
     *
     * @param String $status
     * @return int
     */
    public function orders_count($status = 'completed'){
        $orders_count = wc_orders_count($status);
        return $orders_count;
    }

    /**
     * Return order by id
     *
     * @param Int $id
     * @return Array
     */
    public function get_order_by_id($id){

        $order = wc_get_order( $id );
        $order_post = get_post( $id );

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

    public function get_month_string($id){
      $months = array(
        01 => "Enero",
        02 => "Febrero",
        03 => "Marzo",
        04 => "Abril",
        05 => "Mayo",
        06 => "Junio",
        07 => "Julio",
        08 => "Agosto",
        09 => "Septiembre",
        10 => "Octubre",
        11 => "Noviembre",
        12 => "Diciembre",
      );

      return $months[$id];
    }
}
