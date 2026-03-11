<?php
require_once(__DIR__."/sistema.class.php");
require_once(__DIR__."/models/estado.php"); 
// 1. Necesitamos requerir el archivo de tu modelo Municipio
require_once(__DIR__."/models/municipio.php"); 

$estado = new Estado();
// 2. Necesitamos instanciar el objeto Municipio para poder usarlo en el switch
$municipio = new Municipio();

$id = isset($_GET['id']) ? $_GET['id'] : null;
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

include_once(__DIR__."/views/header.php");

switch($accion){

    case 'crear':
        if(isset($_POST['id_estado'])){
            // 1. Si enviaron el formulario, guardamos los datos
            $data = $_POST;
            $cantidad = $municipio->crear($data);

            // 2. Evaluamos el resultado DENTRO de este bloque
            if($cantidad){
                $municipio->alerta("success", "Municipio creado exitosamente.");
            } else {  
                $municipio->alerta("danger", "Error al crear el municipio.");
            }
            
            // 3. Mostramos la tabla principal
            $municipios = $municipio->leer();
            require(__DIR__."/views/municipios/index.php");

        } else {
            // Si NO enviaron el formulario (método GET), obtenemos los ESTADOS para el dropdown
            $estados_lista = $estado->leer(); 
            
            // Y mostramos el formulario
            require(__DIR__."/views/municipios/formulario_crear.php");
        }
        break;

    case 'actualizar':
        if(isset($_POST['id_estado'])){
            // 1. Si enviaron el formulario, actualizamos
            $data = $_POST;
            $cantidad = $municipio->actualizar($id, $data);

            // 2. Evaluamos el resultado
            if($cantidad){
                $municipio->alerta("success", "Municipio actualizado exitosamente.");
            } else {
                $municipio->alerta("danger", "Error al actualizar el municipio.");
            }

            // 3. Mostramos la tabla principal
            $municipios = $municipio->leer();
            require(__DIR__."/views/municipios/index.php");

        } else {
            // Si NO enviaron el formulario, cargamos los datos del municipio a editar...
            $data = $municipio->leerUno($id);
            
            // ...Y TAMBIÉN cargamos la lista de estados para que el dropdown se muestre correctamente
            $estados_lista = $estado->leer();
            
            require(__DIR__."/views/municipios/formulario_actualizar.php");
        }
        break;

    case 'borrar':
        $cantidad = $municipio->borrar($id);
        
        if($cantidad){
            $municipio->alerta("success", "Municipio borrado exitosamente.");
        } else {
            $municipio->alerta("danger", "Error al borrar el municipio.");
        }
        
        $municipios = $municipio->leer();
        require(__DIR__."/views/municipios/index.php");
        break;
    
    case 'leer':
    default:
        $municipios = $municipio->leer();
        require(__DIR__."/views/municipios/index.php");
        break;
}

include_once(__DIR__."/views/footer.php");
?>