<?php

function validarRutChileno($rut) {
    // Eliminar puntos y guión del rut
    $rut = str_replace('.', '', $rut);
    $rut = str_replace('-', '', $rut);

    // Validar longitud mínima y máxima del rut
    if (strlen($rut) < 8 || strlen($rut) > 10) {
        echo "* NO es el formato correcto, ej: 12.345.678-9<br><br>";
        return null;
    }

    $ultimoDigito = substr($rut, -1);
    if (!ctype_digit($ultimoDigito) || ($ultimoDigito < '1' || $ultimoDigito > '9') && strtoupper($ultimoDigito) !== 'K') {
        echo "* NO es el formato correcto, ej: 12.345.678-9<br><br>";
        return null;
    }
    
    $rutSinUltimoDigito = substr($rut, 0, -1);
    if (!ctype_digit($rutSinUltimoDigito)) {
        echo "* NO es el formato correcto, ej: 12.345.678-9<br><br>";
        return null;
    }
   
    $con = new Conexion();

    $ruts = $con->getRut();
    if (!empty($ruts)) {
        foreach ($ruts as $rutData) {
            if ($rutSinUltimoDigito === $rutData['rut'] && $ultimoDigito === $rutData['dv']) {
                echo "* Ya se encuntra este RUT en la base de datos<br><br>";
                return null;
            }
        }
    }
    
    return $rut;
}

if (!empty($_POST['btn_registro'])){
    $validacionExitosa  = true;
    //Validar nombre
    if (empty($_POST['nombre'])){
        $validacionExitosa  = false;
        echo "* NO está el nombre<br><br>";
    }
    //Validar alias
    $alias = $_POST['alias'];
    if (strlen($alias) <= 5 || !preg_match('/[a-zA-Z]/', $alias) || !preg_match('/\d/', $alias)) {
        $validacionExitosa  = false;
        echo "* El alias debe ser un largo mayor a 5 y contener tanto numeros como letras<br><br>";
    }
    //Validar rut
    $rut = validarRutChileno($_POST['rut']);
    if ( $rut == null){
        $validacionExitosa  = false;
    }

    //Validar email
    if (empty($_POST['email'])){
        $validacionExitosa  = false;
        echo "* NO está el email<br><br>";
    }

    //Validar region
    if (empty($_POST['region'])){
        $validacionExitosa  = false;
        echo "* NO está la region<br><br>";
    }

    //Validar comuna
    if (empty($_POST['comuna'])){
        $validacionExitosa  = false;
        echo "* NO está la comuna<br><br>";
    }

    //Validar candidato
    if (empty($_POST['candidato'])){
        $validacionExitosa  = false;
        echo "* NO está el candidato<br><br>";
    }

    //Validar ¿Cómo se enteró de nosotros?
    $count = 0;
    $opciones = array();
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($_POST['opcion_'.$i])){
            $count += 1;
            $opciones[] = $_POST['opcion_'.$i];
        }
    }
    
    if ($count < 2) {
        $validacionExitosa  = false;
        echo "* En el checkbox debe elegir al menos dos opciones<br><br>";
    }

    if ($validacionExitosa){
        $con = new Conexion();
        $values = [$_POST['nombre'],
        $alias,
        substr($rut, -1),
        substr($rut, 0, -1),
        $_POST['email'],
        $_POST['region'],
        $_POST['comuna'],
        $_POST['candidato'],
        ];
        $con->guardarVoto($values, $opciones);// Llamar a la función guardarVoto()
    }
}
