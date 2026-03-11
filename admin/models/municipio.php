<?php

require_once(__DIR__ . "/../sistema.class.php");

class Municipio extends sistema
{

    function leer(){
        $this->conectar();
        // Traemos todo de municipio (m.*) y el nombre de la columna 'estado' de la tabla estado
        $sql = "SELECT m.*, e.estado AS nombre_estado 
            FROM municipio m 
            INNER JOIN estado e ON m.id_estado = e.id_estado";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerUno($id)
    {
        $this->conectar();
        // Asumo que tu llave primaria es 'id_municipio'
        $sql = "SELECT * FROM municipio WHERE id_municipio = :id_municipio";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_municipio', $id, PDO::PARAM_INT);
        $stmt->execute();
        $municipio = $stmt->fetch(PDO::FETCH_ASSOC);
        return $municipio;
    }

    function crear($data)
    {
        $this->conectar();
        // Ahora el INSERT recibe el nombre del municipio Y la llave foránea
        $sql = "INSERT INTO municipio (municipio, id_estado) VALUES (:municipio, :id_estado)";
        $stmt = $this->db->prepare($sql);

        // Validamos y vinculamos ambos campos.
        // OJO: $data['municipio'] y $data['id_estado'] deben coincidir exactamente 
        // con los atributos 'name' de tus inputs en el formulario HTML.
        $stmt->bindParam(':municipio', $data['municipio'], PDO::PARAM_STR);
        $stmt->bindParam(':id_estado', $data['id_estado'], PDO::PARAM_INT);

        $stmt->execute();
        $cantidad = $stmt->rowCount();
        return $cantidad;
    }

    function actualizar($id, $data)
    {
        $this->conectar();

        // Actualizamos tanto el nombre como el estado al que pertenece
        $sql = "UPDATE municipio 
                SET municipio = :municipio, id_estado = :id_estado 
                WHERE id_municipio = :id_municipio";

        $stmt = $this->db->prepare($sql);

        // Vinculación de parámetros
        $stmt->bindParam(':municipio', $data['municipio'], PDO::PARAM_STR);
        $stmt->bindParam(':id_estado', $data['id_estado'], PDO::PARAM_INT);
        $stmt->bindParam(':id_municipio', $id, PDO::PARAM_INT);

        $stmt->execute();

        $cantidad = $stmt->rowCount();
        return $cantidad;
    }

    function borrar($id)
    {
        $this->conectar();
        $sql = "DELETE FROM municipio WHERE id_municipio = :id_municipio";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_municipio', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>