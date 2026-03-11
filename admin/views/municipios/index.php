<h1>Municipios</h1>
<a href="municipio.php?accion=crear"  class="btn btn-success">Nuevo</a>

<?php if(isset($alerta)): ?>
    <?php require_once(__DIR__ . "/../alerta.php"); ?>
<?php endif; ?>

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Municipio</th>
      <th scope="col">Estado</th>
      <th scope="col">Opciones</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach ($municipios as $municipio):?>
    <tr>
      <th scope="row"><?php echo $municipio['id_municipio']; ?></th>
      
      <td><?php echo $municipio['municipio']; ?></td>
      <td><?php echo $municipio['nombre_estado']; ?></td>
      <td>
    <div class="btn-group" role="group" aria-label="Basic example">
            <a href="municipio.php?accion=actualizar&id=<?php echo $municipio['id_municipio']; ?>" class="btn btn-warning">Editar</a>
            <a href="municipio.php?accion=borrar&id=<?php echo $municipio['id_municipio']; ?>" class="btn btn-danger">Eliminar</a>
          </div>
      </td>
    </tr>
<?php endforeach; ?>
 </tbody>
</table>