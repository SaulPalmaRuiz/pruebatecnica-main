<?php

require('Model/Conexion.php');

$con = Conexion::getInstance();

$regiones = $con->getRegions();
$candidatos = $con->getCandidates();

require('Views/V_registro.php');

?>
