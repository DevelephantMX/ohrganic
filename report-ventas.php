<?php
require_once("wp-load.php");

$month = $_GET["mes"];

$args = array(
    'post_type' => 'shop_order',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'shop_order_status',
            'field' => 'slug',
            'terms' => array( 'completed' )
        )
    ),
    'date_query' => array(
    		array(
      			'month' => $month,
    		),
  	),
);

$posts = get_posts($args);

$orders_collection = array();

foreach($posts as $post){
  $order = get_order_by_id($post->ID);

  array_push($orders_collection, $order);
}

$success = write_csv($orders_collection);
if($success){
  echo $success . "<br /><br />";
  echo "<a href='reportes/reporte-ventas.csv'>Descargar archivo</a>";
}
die("");

/**
 * Return order by id
 *
 * @param Int $id
 * @return Array
 */
function get_order_by_id($id){

    $order = wc_get_order( $id );

    if(!$order){
      return false;
    }

    $order_data = array(
      'id'            => $order->id,
      'order_number'  => $order->get_order_number(),
      'completed_at'  => $order->completed_date,
      'status'        => $order->get_status(),
      'rfc'           => $order->shipping_rfc,
      'first_name'    => $order->billing_first_name,
      'last_name'     => $order->billing_last_name,
      'email'         => $order->billing_email,
      'total'         => wc_format_decimal( $order->get_total(), 2 ),
    );

    return $order_data;
}

/**
 * Creates a CSV file with content received
 *
 * @param Array $collection
 * @return
 */
function write_csv($data){

  $entries = array();

  $headers = array("Núm. órden","RFC","Nombre","Apellidos","Correo electrónico","Total"); //,"Fecha");
  array_push($entries, $headers);
  $i = 0;
  foreach($data as $order){
    $i++;
    $row = array();
    $row[0] = $order["id"];
    $row[1] = $order["rfc"];
    $row[2] = $order["first_name"];
    $row[3] = $order["last_name"];
    $row[4] = $order["email"];
    $row[5] = $order["total"];
    //$row[6] = $order["completed_at"];

    array_push($entries, $row);
  }

  $filename = 'reportes/reporte-ventas.csv';
  $fp = fopen ($filename, "w");

  foreach ( $entries as $row ) {
    fputcsv( $fp, $row );
  }

  // be kind, rewind
  rewind( $fp );

  // cerrar archivo
  fclose( $fp );
  return $i . " order(s) in report";


}
