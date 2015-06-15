<?php

$data = array(
  "success" => true,
  "customer" => array(
    "rfc" => "SOOP900901770",
    "razon_nombre" => "Paul Alejandro Soberanes Osuna",
    "calle" => "Isla Ciclades",
    "num_exterior" => "2595",
    "num_interior" => "4",
    "colonia" => "Jardines del Sur",
    "ciudad" => "Guadalajara",
    "municipio" => "Guadalajara",
    "estado" => "Jalisco",
    "pais" => "Mexico",
    "codigo_postal" => 44950,
    "email" => "paulsoberanes@gmail.com"
  ),
  "invoice" => array(
    "subtotal" => 10900.00,
    "iva" => 345.00,
    "total" => 11245.00
  )
);

echo json_encode($data);
