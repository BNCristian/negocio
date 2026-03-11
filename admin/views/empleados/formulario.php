<h1><?php if ($accion == 'actualizar') echo "Actualizar"; else echo "Crear"; ?> empleado</h1>

<form action="empleado.php?accion=<?php echo $accion; ?><?php if($accion=='actualizar') echo '&id='.$id; ?>" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-4 mb-3"><label class="form-label">Nombre</label>
        <input type="text" class="form-control" name="nombre" placeholder="Nombre del empleado"
        value="<?php echo ($accion == 'actualizar') ? $data['nombre'] : ''; ?>"
        required></div>

        <div class="col-md-4 mb-3"><label class="form-label">Primer apellido</label>
        <input type="text" class="form-control" name="primer_apellido" 
        value="<?php echo ($accion == 'actualizar') ? $data['primer_apellido'] : ''; ?>"
        required></div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Segundo apellido</label>
        <input type="text" class="form-control" name="segundo_apellido" 
        value="<?php echo ($accion == 'actualizar') ? $data['segundo_apellido'] : ''; ?>"
        required></div>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Fecha de nacimiento</label>
            <input type="date" class="form-control" name="fecha_nacimiento" 
            value="<?php echo ($accion == 'actualizar') ? $data['fecha_nacimiento'] : ''; ?>" 
            required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">RFC</label>
            <input type="text" class="form-control" minlength="13" maxlength="13"  name="rfc" 
            placeholder="ABCD123456H01"
            pattern="^[A-Z&]{3,4}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[A-Z0-9]{3}$" 
            value="<?php echo ($accion == 'actualizar') ? $data['rfc'] : ''; ?>"
            required>
        </div>
        
        <div class="col-md-4 mb-3">
            <label class="form-label">CURP</label>
        <input type="text" class="form-control"  pattern="[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9]{2}"
        placeholder="GARM750101HDFRNN01" name="curp" 
        value="<?php echo ($accion == 'actualizar') ? $data['curp'] : ''; ?>"
        required></div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" name="correo" 
        value="<?php echo ($accion == 'actualizar') ? $data['correo'] : ''; ?>"
        required></div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Contraseña <?php if ($accion == 'actualizar') 
                echo "no poner nada para preservar la contraseña actual"; ?></label>
            <input type="password" class="form-control" name="contrasena" 
            <?php if($accion== 'crear') echo 'required'; ?>
            ></div>
        </div>

    <div class="row">
        <div class="col-md-4 mb-3"><label class="form-label">Imagen (archivo) <?php if ($accion == 'actualizar') echo "(No modificar para preservar la imagen actual)"; ?></label>
        <input type="file" class="form-control" name="imagen" <?php if($accion== 'crear') echo 'required'; ?>>
    </div>
        
        <div class="col-md-4 mb-3">
            <label class="form-label">Municipio</label>
            <select class="form-control" name="id_municipio" required>
                <option value="">Selecciona...</option>
                <?php foreach($municipios_lista as $mun): ?>
                    <option value="<?php echo $mun['id_municipio']; ?>" <?php echo ($accion == 'actualizar' && $mun['id_municipio'] == $data['id_municipio']) ? 'selected' : ''; ?>>
                        <?php echo $mun['municipio']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-4 mb-3">
            <label class="form-label">Negocio (Sucursal)</label>
            <select class="form-control" name="id_negocio" required>
                <option value="">Selecciona...</option>
                <?php foreach($negocios_lista as $neg): ?>
                    <option value="<?php echo $neg['id_negocio']; ?>" <?php echo ($accion == 'actualizar' && $neg['id_negocio'] == $data['id_negocio']) ? 'selected' : ''; ?>>
                        <?php echo $neg['negocio']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="mt-3">
        <input type="submit" class="btn btn-primary" name="enviar" value="Guardar">
        <a href="empleado.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>