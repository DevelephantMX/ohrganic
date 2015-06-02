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

    $params = array(
      "rfc" => $rfc
    );

    $invoice = $this->callCurl($this->url, 'GET', $params);

    if($invoice->success != true){
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

  public function set_invoice_api($data, $order){

    $params = array(
      "rfc" => $data["rfc"],
      "nombre" => $data["nombre"],
      "calle" => $data["calle"],
      "exterior" => $data["exterior"],
      "interior" => $data["interior"],
      "colonia" => $data["colonia"],
      "municipio" => $data["municipio"],
      "estado" => $data["estado"],
      "pais" => $data["pais"],
      "cp" => $data["cp"],
      "pedido" => $order
    );

    //hacer llamada curl a factura.com

    return $params;


  }

  private function callCurl($url, $method, $params){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if($method == "POST"){
      curl_setopt($this->_curl_handle, CURLOPT_POST, count($params));
      curl_setopt($this->_curl_handle, CURLOPT_POSTFIELDS, json_encode($params));
      curl_setopt($this->_curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    }

    $data = curl_exec($ch);
    curl_close($ch);

    //convert to array
    return json_decode($data);

  }



}
