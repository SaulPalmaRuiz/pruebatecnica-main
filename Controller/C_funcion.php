<?php

// Obtener el valor de la región enviado desde JavaScript
$selectedRegion = $_GET['region']; // si se envió como parte de la URL con el método GET

require_once('../Model/Conexion.php');

$con = new Conexion();

$comunas = $con->getCommunesByRegion($selectedRegion);

// Enviar el resultado como una respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($comunas);