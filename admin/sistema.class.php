<?php
require_once(__dir__."/config.php");

class sistema{
    
    var $db;

    public function __construct() {
        $this->conectar();

    }

    public function getDB(){
        return $this->db;
    }

    public function getExtensionesImagenes(){
        return array("image/jpeg","image/jpg", "image/png", "image/gif", "image/webp","image/gif");  
    }
        
    function conectar(){
       $this->db = new PDO(dbdriver.":host=".dbhost.";dbname=".dbname,dbuser, dbpassword);

    }
    function alerta($tipo, $mensaje){
        $alerta=array();
        $alerta['tipo']=$tipo;
        $alerta['mensaje']=$mensaje;
        include(__DIR__."/views/alerta.php");
        
        }

    function cargarFotografia($nombre,$path, $data){
        $origen=$_FILES($nombre,'tmp_name');
        $parts = explode('.', $_FILES('name'));
        $extension = end($parts); 
        $nombre='/../uploads'.$path.'/'.$data['rfc'].'.'.$extension;
        $destino=__DIR__.'/../uploads'.$path.'/'.$data['rfc'].'.'.$extension;
        if(move_uploaded_file($origen, $destino)){
            return $nombre;
        }
        return null;
    }
};

