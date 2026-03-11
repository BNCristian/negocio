<?php
require_once(__DIR__ . "/../sistema.class.php");

class Negocio extends sistema {
    function leer(){
        $this->conectar();
        $sql = "SELECT n.*, m.municipio AS nombre_municipio 
            FROM negocio n 
            INNER JOIN municipio m ON n.id_municipio = m.id_municipio";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerUno($id){
        $this->conectar();
        $sql = "SELECT * FROM negocio WHERE id_negocio = :id_negocio";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_negocio', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data){
        $this->conectar();
        $sql = "INSERT INTO negocio (negocio, id_municipio) VALUES (:negocio, :id_municipio)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':negocio', $data['negocio'], PDO::PARAM_STR);
        $stmt->bindParam(':id_municipio', $data['id_municipio'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    function actualizar($id, $data){
        $this->conectar();
        $sql = "UPDATE negocio SET negocio = :negocio, id_municipio = :id_municipio WHERE id_negocio = :id_negocio";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':negocio', $data['negocio'], PDO::PARAM_STR);
        $stmt->bindParam(':id_municipio', $data['id_municipio'], PDO::PARAM_INT);
        $stmt->bindParam(':id_negocio', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    function borrar($id){
        $this->conectar();
        $sql = "DELETE FROM negocio WHERE id_negocio = :id_negocio";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_negocio', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>