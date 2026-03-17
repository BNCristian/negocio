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
}
;

