<?php
require_once(__DIR__."/sistema.class.php");
require_once(__DIR__."/models/empleado.php"); 
require_once(__DIR__."/models/municipio.php"); 
require_once(__DIR__."/models/negocio.php"); 

$empleado = new Empleado(); // Objeto intacto
$empleado->checarRol('Administrador');
$municipio = new Municipio();
$negocio = new Negocio();

$id = isset($_GET['id']) ? $_GET['id'] : null;
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

include_once(__DIR__."/views/header.php");


$empleados = []; 

switch($accion){
    case 'crear':
        if(isset($_POST['nombre'])){ 
            $data = $_POST;
            $resultado = $empleado->crear($data);

            if($resultado === "correo_duplicado"){
                $empleado->alerta("warning", "El correo ya está registrado en el sistema. Intenta con otro.");
            } else if($resultado){
                $empleado->alerta("success", "Empleado y usuario creados exitosamente.");
            } else {  
                $empleado->alerta("danger", "Error al crear el empleado.");
            }
            
            $empleados = $empleado->leer();
            require(__DIR__."/views/empleados/index.php");

        } else {
            $municipios_lista = $municipio->leer();
            $negocios_lista = $negocio->leer();
            require(__DIR__."/views/empleados/formulario.php");
        }
        break;

    case 'actualizar':
        if(isset($_POST['nombre'])){ 
            $data = $_POST;
            $cantidad = $empleado->actualizar($id, $data);

            if($cantidad){
                $empleado->alerta("success", "Empleado actualizado exitosamente.");
            } else {  
                $empleado->alerta("danger", "Error al actualizar el empleado. (O no cambiaste ningún dato)");
            }
            
            $empleados = $empleado->leer();
            require(__DIR__."/views/empleados/index.php");

        } else {
            $data = $empleado->leerUno($id);
            $municipios_lista = $municipio->leer();
            $negocios_lista = $negocio->leer();
            require(__DIR__."/views/empleados/formulario.php");
        }
        break;

    case 'borrar':
        $cantidad = $empleado->borrar($id);
        if($cantidad){ $empleado->alerta("success", "Empleado borrado exitosamente."); } 
        else { $empleado->alerta("danger", "Error al borrar el empleado."); }
        
        $empleados = $empleado->leer();
        require(__DIR__."/views/empleados/index.php");
        break;
    
    case 'leer':
    default:
        $empleados = $empleado->leer();
        require(__DIR__."/views/empleados/index.php");
        break;
}

include_once(__DIR__."/views/footer.php");
?>