<?php

require_once 'controlador/controlador.php';
$controlador = new controlador();

if($_GET && isset($_GET['accion'])){
    $accion =filter_input(INPUT_GET, "accion", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if(method_exists($controlador,$accion)){
        $controlador->$accion();
    }else{
        $controlador->index();
    }
}else{
    $controlador->index();
}
?>
