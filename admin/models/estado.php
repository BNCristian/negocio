<?php

require_once(__dir__."/../sistema.class.php");
class Estado extends sistema{
    function leer(){

        $this->conectar();
        $sql = "SELECT * FROM estado";
        $stmt = $this->getDB()->query($sql);
        $stmt->execute();
        $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $estados;


    }

    function leerUno($id_estado){
        $this->conectar();
        $sql = "SELECT * FROM estado WHERE id_estado = :id_estado";
        
        $stmt = $this->getDB()->query($sql);
        $stmt->bindParam(":id_estado", $id_estado, PDO::PARAM_INT);
        $stmt->execute();
        $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $estados;
    }

    function leerTodo(){
    $this->conectar();
    $sql = "SELECT id_estado, estado FROM estado"; 
    $stmt = $this->getDB()->query($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultados;
    }

    function crear($data){
        $this->conectar();
        $sql = "INSERT INTO estado (estado) VALUES (:nombre)";
        $stmt = $this->getDB()->query($sql);
        $stmt->bindParam(":nombre", $data['estado'], PDO::PARAM_STR);
        $stmt->execute();
        $cantidad = $stmt->rowCount(); 
        return $cantidad;
    }

    function actualizar($id_estado, $data){
        $this->conectar();
        $sql = "UPDATE estado SET estado = :nombre WHERE id_estado = :id_estado";
        $stmt = $this->getDB()->query($sql);
        $stmt->bindParam(":id_estado", $id_estado, PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $data['estado'], PDO::PARAM_STR);
        $resultado = $stmt->execute();
        return $resultado;
    }
    
    function borrar($id_estado){
        $this->conectar();
        $sql = "DELETE FROM estado WHERE id_estado = :id_estado";
        $stmt = $this->getDB()->query($sql);
        $stmt->bindParam(":id_estado", $id_estado, PDO::PARAM_INT);
        $resultado = $stmt->execute();
        return $resultado;

    }   
}