<h1>Negocios</h1>
<a href="negocio.php?accion=crear" class="btn btn-success mb-3">Nuevo</a>
<?php if(isset($alerta)): require_once(__DIR__ . "/../alerta.php"); endif; ?>
<div class="table-responsive">
    <table class="table table-hover table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th># ID</th>
          <th>Nombre del Negocio</th>
          <th>Municipio</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
    <?php foreach ($negocios as $neg): ?>
        <tr>
          <th><?php echo $neg['id_negocio']; ?></th>
          <td><?php echo $neg['negocio']; ?></td>
          <td><?php echo $neg['nombre_municipio']; ?></td>
          <td>
            <div class="btn-group">
                <a href="negocio.php?accion=actualizar&id=<?php echo $neg['id_negocio']; ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="negocio.php?accion=borrar&id=<?php echo $neg['id_negocio']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
            </div>
          </td>
        </tr>
    <?php endforeach; ?>
     </tbody>
    </table>
</div>