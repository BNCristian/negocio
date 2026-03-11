<?php
require_once(__DIR__."/sistema.class.php");
require_once(__DIR__."/models/negocio.php"); 
require_once(__DIR__."/models/municipio.php"); 

$negocio = new Negocio();
$municipio = new Municipio(); 

$id = isset($_GET['id']) ? $_GET['id'] : null;
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

include_once(__DIR__."/views/header.php");

switch($accion){
    case 'crear':
        if(isset($_POST['negocio'])){ 
            $data = $_POST;
            $cantidad = $negocio->crear($data);
            if($cantidad){ $negocio->alerta("success", "Negocio creado exitosamente."); } 
            else { $negocio->alerta("danger", "Error al crear el negocio."); }
            $negocios = $negocio->leer();
            require(__DIR__."/views/negocios/index.php");
        } else {
            $municipio_lista = $municipio->leer(); 
            require(__DIR__."/views/negocios/formulario_crear.php");
        }
        break;
    case 'actualizar':
        if(isset($_POST['negocio'])){ 
            $data = $_POST;
            $cantidad = $negocio->actualizar($id, $data);
            if($cantidad){ $negocio->alerta("success", "Negocio actualizado exitosamente."); } 
            else { $negocio->alerta("danger", "Error al actualizar el negocio."); }
            $negocios = $negocio->leer();
            require(__DIR__."/views/negocios/index.php");
        } else {
            $data = $negocio->leerUno($id);
            $municipio_lista = $municipio->leer(); 
            require(__DIR__."/views/negocios/formulario_actualizar.php");
        }
        break;
    case 'borrar':
        $cantidad = $negocio->borrar($id);
        if($cantidad){ $negocio->alerta("success", "Negocio borrado exitosamente."); } 
        else { $negocio->alerta("danger", "Error al borrar el negocio."); }
        $negocios = $negocio->leer();
        require(__DIR__."/views/negocios/index.php");
        break;
    case 'leer':
    default:
        $negocios = $negocio->leer();
        require(__DIR__."/views/negocios/index.php");
        break;
}
include_once(__DIR__."/views/footer.php");
?>