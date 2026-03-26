<?php
require_once(__DIR__ . "/config.php");

class sistema
{

    var $db;

    public function __construct()
    {
        $this->conectar();

    }

    public function getDB()
    {
        return $this->db;
    }

    public function getExtensionesImagenes()
    {
        return array("image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp", "image/gif");
        include(__DIR__ . "/views/alerta.php");
    }

    function conectar()
    {
        $this->db = new PDO(dbdriver . ":host=" . dbhost . ";dbname=" . dbname, dbuser, dbpassword);

    }
    function alerta($tipo, $mensaje)
    {
        $alerta = array();
        $alerta['tipo'] = $tipo;
        $alerta['mensaje'] = $mensaje;
        include(__DIR__ . "/views/alerta.php");

    }

    function cargarFotografia($nombre, $path, $data)
    {
        if (isset($_FILES[$nombre])) {
            if ($_FILES[$nombre]["error"] == 0) {
                if (in_array($_FILES[$nombre]["type"], $this->getExtensionesImagenes())) {
                    if ($_FILES[$nombre]["size"] <= 5000000) {
                        $origen = $_FILES[$nombre]['tmp_name'];
                        $parts = explode('.', $_FILES[$nombre]['name']);
                        $extension = end($parts);
                        $nombre = '/uploads/' . $path . '/' . $data['rfc'] . '.' . $extension;

                        // La ruta física absoluta en el servidor: ej. "/var/www/html/negocio/admin/../uploads/empleado/RFC.jpg"
                        $destino = __DIR__ . '/../uploads/' . $path . '/' . $data['rfc'] . '.' . $extension;
                        if (move_uploaded_file($origen, $destino)) {
                            return $nombre;
                        }
                    }
                }
            }
        }


        return null;
    }

    function getRoles($correo){
        $sql = "SELECT r.rol FROM rol r INNER JOIN usuario_rol ur ON r.id_rol = ur.id_rol 
                INNER JOIN usuario u ON ur.id_usuario = u.id_usuario
                WHERE u.correo = :correo;";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);        
    }

    function getPermisos($correo)
    {
        $sql = "SELECT p.permiso FROM rol r INNER JOIN usuario_rol ur ON r.id_rol = ur.id_rol 
                INNER JOIN usuario u ON ur.id_usuario = u.id_usuario
                INNER JOIN rol_permiso rp ON r.id_rol = rp.id_rol
                INNER JOIN permiso p ON rp.id_permiso = p.id_permiso
                WHERE u.correo = :correo;";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    function login($correo, $contraseña){
        $contraseña_md5 = md5($contraseña);
        
        // Cambié el marcador :contraseña por :pass para que PDO no llore con la "ñ"
        $sql ="SELECT * FROM usuario WHERE correo = :correo AND contraseña = :pass;";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
        $stmt->bindParam(":pass", $contraseña_md5, PDO::PARAM_STR);
        
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(isset($usuario['correo'])){
            // Iniciamos sesión si no está iniciada
            if (session_status() == PHP_SESSION_NONE) { session_start(); }
            
            $_SESSION['validado'] = true;
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['roles'] = $this->getRoles($correo);
            $_SESSION['permisos'] = $this->getPermisos($correo);
            return true;
        } else {
            if (session_status() == PHP_SESSION_NONE) { session_start(); }
            session_destroy();
            return false;
        }
    }

    function logout(){
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        unset($_SESSION);
        session_destroy();
    }

    function checarRol($rol){
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        
        if(isset($_SESSION['validado']) && $_SESSION['validado'] === true){
            $roles = $_SESSION['roles'];
            // Convertimos todo a minúsculas para que no haya falla (Administrador vs administrador)
            $roles_lower = array_map('strtolower', $roles);
            
            if(in_array(strtolower($rol), $roles_lower)){
                return true;
            }
        }
        
        // Si no tiene el rol, lo manda a volar
        require_once(__DIR__ . "/views/header.php");
        echo '<div class="container mt-5">';
        $this->alerta('error', 'No tienes permiso para acceder a esta seccion. <a href="login.php?accion=logout" class="alert-link">Cerrar sesión e intentar de nuevo</a>');
        echo '</div>';
        require_once(__DIR__ . "/views/footer.php");
        die(); 
    }

    public function validarPermiso($permiso) {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }
        
        if(isset($_SESSION['validado']) && $_SESSION['validado'] == true){
            $permisos = $_SESSION['permisos'];
            if(in_array($permiso, $permisos)){
                return true;
            }
        }
        return false;
    }

    // --- FUNCIÓN PARA ENVIAR CORREOS (SPRINT 06) ---
    public function envioCorreo($nombre, $destinatario, $asunto, $cuerpo, $adjuntos = null)
    {
        // El include del autoload como pidió el profe
        require_once(__DIR__ . '/../vendor/autoload.php');

        // Instanciamos la clase de PHPMailer directo
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // Smtp debug off
            $mail->SMTPDebug = 0;
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            
            // 👇 ¡AQUÍ PON TUS DATOS PARA LAS PRUEBAS! 👇
            $mail->Username   = '21031428@itcelaya.edu.mx'; 
            $mail->Password   = 'pon tu key de aplicación aquí'; 
            
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587; 

            // De quién viene y a quién va
            $mail->setFrom('21031428@itcelaya.edu.mx', 'COCINA DE MARIA');
            $mail->addAddress($destinatario, $nombre);

            // Contenido (Message HTML)
            $mail->isHTML(true); 
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpo;

            // Adjuntos (dejado comentado/preparado para el futuro)
            if ($adjuntos != null) {
                // $mail->addAttachment($adjuntos);
            }

            $mail->send();
            return true; // Return true en lugar de echo
            
        } catch (\Exception $e) {
            return false; // Return false en caso de error
        }
    }
}

