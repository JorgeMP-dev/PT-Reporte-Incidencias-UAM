<?php
function tienePermiso($modulo, $nivelRequerido){
    
    if(!isset($_SESSION['permisos'][$modulo])){
        return false;
    }

    $niveles = [
        "ADMINISTRADOR" => 1,
        "EDICION" => 2,
        "LECTURA" => 3
    ];

    return $niveles[$_SESSION['permisos'][$modulo]] 
           <= $niveles[$nivelRequerido];
}
?>