<?php
require_once(__DIR__ . "/../sistema.class.php");

class Empleado extends sistema {
    function leer() {

        $this->conectar();
        $sql = "SELECT e.*, u.correo, m.municipio AS nombre_municipio, n.negocio AS nombre_negocio 
                FROM empleado e 
                INNER JOIN usuario u ON e.id_usuario = u.id_usuario
                LEFT JOIN municipio m ON e.id_municipio = m.id_municipio
                LEFT JOIN negocio n ON e.id_negocio = n.id_negocio";
        $stmt = $this->getDB()->query($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerUno($id) {
        $this->conectar();
        $sql = "SELECT e.*, u.correo, m.municipio AS nombre_municipio, n.negocio AS nombre_negocio 
                FROM empleado e 
                INNER JOIN usuario u ON e.id_usuario = u.id_usuario
                LEFT JOIN municipio m ON e.id_municipio = m.id_municipio
                LEFT JOIN negocio n ON e.id_negocio = n.id_negocio
                WHERE e.id_empleado = :id_empleado";
        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data) {
        $this->conectar();
        $this->getDB()->beginTransaction();
        
        try {
            $sqlCheck = "SELECT id_usuario FROM usuario WHERE correo = :correo";
            $stmtCheck = $this->getDB()->prepare($sqlCheck);
            $stmtCheck->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
            $stmtCheck->execute();
            
            if ($stmtCheck->rowCount() > 0) {
                $this->getDB()->rollBack();
                return "correo_duplicado"; 
            }

            
            $sqlUser = "INSERT INTO usuario (correo, contraseña) VALUES (:correo, :contrasena)";
            $stmtUser = $this->getDB()->prepare($sqlUser);
            $stmtUser->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
            $stmtUser->bindParam(':contrasena', $data['contrasena'], PDO::PARAM_STR);
            $stmtUser->execute();
            
            
            $id_usuario = $this->getDB()->lastInsertId();

            $fotografia=$this->cargarFotografia('empleado',$data);

            $sqlEmp = "INSERT INTO empleado (nombre, primer_apellido, segundo_apellido, fecha_nacimiento, 
            rfc, curp, imagen, id_municipio, id_usuario, id_negocio) 
                       VALUES (:nombre, :primer_apellido, :segundo_apellido, :fecha_nacimiento, 
            :rfc, :curp, :imagen, :id_municipio, :id_usuario, :id_negocio)";
            $stmtEmp = $this->getDB()->prepare($sqlEmp);
            
            $stmtEmp->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmtEmp->bindParam(':primer_apellido', $data['primer_apellido'], PDO::PARAM_STR);
            $stmtEmp->bindParam(':segundo_apellido', $data['segundo_apellido'], PDO::PARAM_STR);
            $stmtEmp->bindParam(':fecha_nacimiento', $data['fecha_nacimiento'], PDO::PARAM_STR);
            $stmtEmp->bindParam(':rfc', $data['rfc'], PDO::PARAM_STR);
            $stmtEmp->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
            $stmtEmp->bindParam(':imagen', $fotografia['imagen'], PDO::PARAM_STR);
            $stmtEmp->bindParam(':id_municipio', $data['id_municipio'], PDO::PARAM_INT);
            $stmtEmp->bindParam(':id_negocio', $data['id_negocio'], PDO::PARAM_INT);
            $stmtEmp->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT); // Usamos el ID nuevo
            
            $stmtEmp->execute();
            
            // Si todo salió bien, guardamos los cambios reales
            $this->getDB()->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->getDB()->rollBack();
            return false;
        }
    }

    function actualizar($id, $data) {
        $this->conectar();
        $this->getDB()->beginTransaction();
        $cantidad = 0;
        $empleado=$this->leerUno($id);
        try {
            if(!$this->inTransaction()){
                $this->getDB()->beginTransaction();
            }

        if (!$empleado['correo'] == $data['correo']) {
            $sql = 'SELECT correo FROM usuario WHERE correo = :correo';
            $stmt = $this->getDB()->prepare($sql);
            $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->getDB()->rollBack();
                return "correo_duplicado";
            }
        }else {

                $sql = "UPDATE empleado 
                        SET nombre = :nombre, primer_apellido = :primer_apellido, segundo_apellido = :segundo_apellido, 
                            fecha_nacimiento = :fecha_nacimiento, rfc = :rfc, curp = :curp, imagen = :imagen, 
                            id_municipio = :id_municipio, id_negocio = :id_negocio 
                        WHERE id_empleado = :id_empleado"; 
                $stmt = $this->getDB()->prepare($sql);
        }

        if(isset($data['contrasena']) && !empty($data['contrasena'])){
            $data['contrasena'] = md5($data['contrasena']);
            $sql='update usuario set contraseña=:contrasena where id_usuario=:id_usuario';
            $stmt2=$this->getDB()->prepare($sql);
            $stmt2->bindParam(':contrasena', $data['contrasena'], PDO::PARAM_STR);
            $stmt2->bindParam(':correo', $empleado['correo'], PDO::PARAM_STR);
            $stmt2->execute();
        }

                $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
                $stmt->bindParam(':primer_apellido', $data['primer_apellido'], PDO::PARAM_STR);
                $stmt->bindParam(':segundo_apellido', $data['segundo_apellido'], PDO::PARAM_STR);
                $stmt->bindParam(':fecha_nacimiento', $data['fecha_nacimiento'], PDO::PARAM_STR);
                $stmt->bindParam(':rfc', $data['rfc'], PDO::PARAM_STR);
                $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
                $stmt->bindParam(':imagen', $data['imagen'], PDO::PARAM_STR);
                $stmt->bindParam(':id_municipio', $data['id_municipio'], PDO::PARAM_INT);
                $stmt->bindParam(':id_negocio', $data['id_negocio'], PDO::PARAM_INT);
                $stmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
                
                

                $stmt->execute();
            return $stmt->rowCount();
        }catch (PDOException $e) {
            $this->getDB()->rollBack();
            return false;
        }
    }

    function borrar($id) {
        $this->conectar();
        // Borramos primero al empleado
        $sql = "DELETE FROM empleado WHERE id_empleado = :id_empleado";
        $stmt = $this->getDB()->query($sql);query: 
        $stmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>