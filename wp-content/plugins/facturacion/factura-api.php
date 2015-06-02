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
    return $invoice;
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

    return json_decode($data);

  }



}
