<h1>Nuevo negocio</h1>
<form action="negocio.php?accion=crear" method="POST">
    <div class="mb-3">
        <label for="negocio" class="form-label">Nombre del negocio</label>
        <input type="text" class="form-control" id="negocio" name="negocio" required>
    </div>
    <div class="mb-3">
        <label for="id_municipio" class="form-label">Municipio al que pertenece</label>
        <select class="form-control" id="id_municipio" name="id_municipio" required>
            <option value="">Selecciona un municipio...</option>
            <?php foreach($municipio_lista as $mun): ?>
                <option value="<?php echo $mun['id_municipio']; ?>"><?php echo $mun['municipio']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <input type="submit" class="btn btn-primary" name="enviar" value="Guardar">
    <a href="negocio.php" class="btn btn-secondary">Cancelar</a>
</form>