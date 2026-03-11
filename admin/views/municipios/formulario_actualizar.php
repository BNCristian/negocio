<h1>Actualizar municipio</h1>

<form action="municipio.php?accion=actualizar&id=<?php echo $_GET['id']; ?>" method="POST">
    
    <div class="mb-3">
        <label for="municipio" class="form-label">Nombre del municipio</label>
        <input type="text" class="form-control" id="municipio" name="municipio" value="<?php echo $data['municipio']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="id_estado" class="form-label">Estado al que pertenece</label>
        <select class="form-control" id="id_estado" name="id_estado" required>
            <option value="">Selecciona un estado...</option>
            
            <?php foreach($estados_lista as $estado): ?>
                <option value="<?php echo $estado['id_estado']; ?>" <?php echo ($estado['id_estado'] == $data['id_estado']) ? 'selected' : ''; ?>>
                    <?php echo $estado['estado']; ?>
                </option>
            <?php endforeach; ?>
            
        </select>
    </div>

    <input type="submit" class="btn btn-primary" name="enviar" value="Guardar">
    <a href="municipio.php" class="btn btn-secondary">Cancelar</a>
    
</form>