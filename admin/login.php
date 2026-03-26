<?php
require_once(__DIR__."/sistema.class.php");
$app = new sistema();

$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

switch ($accion) {
    case 'login':
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];
        
        // Llamamos a la función de sistema.class.php
        if ($app->login($correo, $contrasena)) {
            // Quitamos la alerta. Si el login es correcto, lo mandamos directo a empleados
            header("Location: empleado.php");
            exit();
        } else {
            $app->alerta('danger', 'Correo o contraseña incorrectos');
            require_once(__DIR__."/views/login/index.php"); // Tu vista HTML
        }
        break;

    case 'logout':
        $app->logout();
        $app->alerta('success', 'Sesión cerrada correctamente');
        require_once(__DIR__."/views/login/index.php");
        break;

    default:
        // Si no hay acción, solo mostramos el formulario
        require_once(__DIR__."/views/login/index.php");
        break;
}
?>