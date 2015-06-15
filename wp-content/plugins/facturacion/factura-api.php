<?php

class FacturaApi {

  private $url;
  private $key;
  private $secret;

  function __construct($url, $key, $secret) {
    $this->url    = $url;
    $this->key    = $key;
    $this->secret = $secret;
	}

  public function get_invoice_api($rfc){

    $api_url = $this->url . $rfc;

    $invoice = $this->callCurl($api_url, 'GET');

    if($invoice->status != "success"){
      $error_class = "data-warning";
      $error_msg   = "El RFC no se encuentra registrado. Por favor ingrese sus datos.";
    }else{
      $error_class = "data-info";
      $error_msg   = "Por favor verifique sus datos fiscales, en caso de ser erroneos, puede editarlos.";
    }

    $response = array(
      "class" => $error_class,
			"message" => $error_msg,
			"invoice_data" => $invoice
    );
    return $response;
  }

  public function create_client_api($data, $order){

    $params = array(
      "nombre"          => $data["g_nombre"],
      "apellidos"       => $data["g_apellidos"],
      "email"           => $data["g_email"],
      "telefono"        => $data["f_telefono"],
      "razons"          => $data["f_nombre"],
      "rfc"             => $data["f_rfc"],
      "calle"           => $data["f_calle"],
      "numero_exterior" => $data["f_exterior"],
      "numero_interior" => $data["f_interior"],
      "codpos"          => $data["f_cp"],
      "colonia"         => $data["f_colonia"],
      "estado"          => $data["f_estado"],
      "ciudad"          => $data["f_municipio"],
      "delegacion"      => $data["f_delegacion"],
      "save"            => true,
    );

    $response = $this->callCurl($this->url, "POST", $params);
    return $response;
  }

  public function generate_invoice($customer, $order){

    $items = array();
    foreach($order["line_items"] as $item){
      $unidad = ($item["product_id"] == 31) ? "Servicio" : "Producto";

      $product = array(
        "cantidad"  => $item["quantity"],
        "unidad"    => $unidad,
        "concept"   => $item["name"],
        "precio"    => $item["price"],
        "subtotal"  => $item["subtotal"],
      );

      array_push($items, $product);
    }

    $payment_method = ($order["payment_details"]["method_id"] == "bacs") ? "Depósito en Cuenta" : "Pago con Tarjeta";

    $params = array(
      "rfc"           => $customer["RFC"],
      "items"         => $items,
      "numerocuenta"  => "No identificado",
      "formapago"     => "Pago en una Sola Exhibición",
      "metodopago"    => $payment_method,
      "currencie"     => $order["currency"],
      "iva"           => "TRUE",
      "num_order"     => $order["id"],
      "seriefactura"  => "F",
      "save"          => "true"
    );

    $response = $this->callCurl($this->url, "POST", $params);
    return $response;
  }

  private function callCurl($url, $method, $params = null){
    $key    = $this->key;
    $secret = $this->secret;

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','F-API-KEY:'.$key, 'F-SECRET-KEY:'.$secret));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if($method == "POST"){
      $data_string = json_encode($params);

      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length:'.strlen($data_string),'F-API-KEY:'.$key, 'F-SECRET-KEY:'.$secret));
    }

    $fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w');
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_STDERR, $fp);

    $data = curl_exec($ch);
    curl_close($ch);

    //convert to array
    return json_decode($data);
  }



}
