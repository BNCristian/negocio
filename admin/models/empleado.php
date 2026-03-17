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

    function actualizar($id_empleado, $data){
        $empleado = $this->leerUno($id_empleado);
        
        try {
            // Iniciamos la transacción correctamente apuntando a la BD
            $this->getDb()->beginTransaction();
            
            // 1. Verificamos si cambió el correo
            if ($empleado['correo'] !== $data['correo']) {
                $sql = "SELECT id_usuario FROM usuario WHERE correo = :correo";
                $stmt = $this->getDb()->prepare($sql);
                $stmt->bindValue(':correo', $data['correo'], PDO::PARAM_STR);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    throw new Exception("El correo ya existe");
                }
                
                // Actualizamos el correo en la tabla usuario
                $sql = "UPDATE usuario SET correo = :correo WHERE id_usuario = :id_usuario";
                $stmt = $this->getDb()->prepare($sql);
                $stmt->bindValue(':correo', $data['correo'], PDO::PARAM_STR);
                $stmt->bindValue(':id_usuario', $empleado['id_usuario'], PDO::PARAM_INT);
                $stmt->execute();
            }

            // 2. Verificamos si puso contraseña nueva
            if (isset($data['password']) && !empty(trim($data['password']))) {
                $sql = "UPDATE usuario SET contrasena = :contrasena WHERE id_usuario = :id_usuario";
                $stmt = $this->getDb()->prepare($sql);
                $stmt->bindValue(':contrasena', md5($data['password']), PDO::PARAM_STR);
                $stmt->bindValue(':id_usuario', $empleado['id_usuario'], PDO::PARAM_INT);
                $stmt->execute();
            }

            // 3. Subimos la foto si es que seleccionó una nueva en el formulario
            $fotografia = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $fotografia = $this->cargarFotografia('imagen', 'empleado', $data);
            }

            // 4. Actualizamos al empleado
            $sql = "UPDATE empleado SET
                nombre = :nombre,
                primer_apellido = :primer_apellido,
                segundo_apellido = :segundo_apellido,
                fecha_nacimiento = :fecha_nacimiento,
                rfc = :rfc,
                curp = :curp,
                imagen = :imagen,
                id_municipio = :id_municipio,
                id_negocio = :id_negocio
                WHERE id_empleado = :id_empleado";
            
            $stmt = $this->getDb()->prepare($sql);
            $stmt->bindValue(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindValue(':primer_apellido', $data['primer_apellido'], PDO::PARAM_STR);
            
            // Si el segundo apellido está vacío, le pasamos un NULL real a la BD
            if (!empty($data['segundo_apellido'])) {
                $stmt->bindValue(':segundo_apellido', $data['segundo_apellido'], PDO::PARAM_STR);
            } else {
                $stmt->bindValue(':segundo_apellido', null, PDO::PARAM_NULL);
            }
            
            $stmt->bindValue(':fecha_nacimiento', $data['fecha_nacimiento'], PDO::PARAM_STR);
            $stmt->bindValue(':rfc', $data['rfc'], PDO::PARAM_STR);
            $stmt->bindValue(':curp', $data['curp'], PDO::PARAM_STR);
            
            // Si subió foto nueva la guardamos, si no, rescatamos la que ya tenía antes
            if ($fotografia !== null) {
                $stmt->bindValue(':imagen', $fotografia, PDO::PARAM_STR);
            } else {
                $stmt->bindValue(':imagen', $empleado['imagen'], PDO::PARAM_STR);
            }
            
            $stmt->bindValue(':id_municipio', $data['id_municipio'], PDO::PARAM_INT);
            $stmt->bindValue(':id_negocio', $data['id_negocio'], PDO::PARAM_INT);
            $stmt->bindValue(':id_empleado', $id_empleado, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // Si llegamos hasta aquí sin que nada explote, guardamos los cambios definitivamente
            $this->getDb()->commit();
            return true;
            
        } catch (Exception $e) {
            // Si algo falla, damos reversa a todo
            $this->getDb()->rollback();
            throw new Exception($e->getMessage());
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