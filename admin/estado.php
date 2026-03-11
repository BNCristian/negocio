<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/estado.php");
$estado = new Estado();


$id = (isset($_GET['id'])) ? $_GET['id'] : null;
$accion = (isset($_GET['accion'])) ? $_GET['accion'] : null;

include_once(__DIR__ . "/views/header.php");

switch ($accion) {
    case 'crear':
        if (isset($_POST['estado'])) {
            $data = $_POST;
            $cantidad = $estado->crear($data);
            
            // 1. Preparamos el arreglo de la alerta (Verde = success)
            if ($cantidad) { 
                $alerta = [
                    'tipo' => 'success', 
                    'mensaje' => 'Se agregó el estado correctamente.'
                ]; 
            }
            
            // 2. Leemos la BD y cargamos la tabla
            $estados = $estado->leer();
            require(__DIR__ . "/views/estados/index.php");
        } else {
            require(__DIR__ . "/views/estados/formulario_crear.php");
        }
        break;

    case 'actualizar':
        if (isset($_POST['estado'])) {
            $data = $_POST;
            $cantidad = $estado->actualizar($id, $data);
            
            // 1. Preparamos el arreglo de la alerta
            if ($cantidad) { 
                $alerta = [
                    'tipo' => 'success', 
                    'mensaje' => 'Se actualizó el estado correctamente.'
                ]; 
            }
            
            // 2. Leemos la BD y cargamos la tabla (¡Adiós pantalla blanca!)
            $estados = $estado->leer();
            require(__DIR__ . "/views/estados/index.php");
        } else {
            $data = $estado->leerUno($id);
            require(__DIR__ . "/views/estados/formulario_actualizar.php");
        }
        break;

    case 'borrar':
        $cantidad = $estado->borrar($id);
        
        // 1. Preparamos la alerta para borrar (Rojo = danger, o success si prefieres)
        if ($cantidad) { 
            $alerta = [
                'tipo' => 'success', 
                'mensaje' => 'Se eliminó el estado correctamente.'
            ]; 
        }
        
        // 2. Leemos la BD y cargamos la tabla
        $estados = $estado->leer();
        require(__DIR__ . "/views/estados/index.php");
        break;

    case 'leer':
    default:
        $estados = $estado->leer();
        require(__DIR__ . "/views/estados/index.php");
        break;
}
include_once(__DIR__ . "/views/footer.php");
?>