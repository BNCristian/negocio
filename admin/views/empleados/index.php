<h1>Empleados</h1>
<a href="empleado.php?accion=crear" class="btn btn-success mb-3">Nuevo</a>

<?php if(isset($alerta)): require_once(__DIR__ . "/../alerta.php"); endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Nombre Completo</th>
          <th scope="col">RFC</th>
          <th scope="col">Correo</th>
          <th scope="col">Municipio</th>
          <th scope="col">Negocio</th>
          <th scope="col">Opciones</th>
        </tr>
      </thead>
      <tbody>
    <?php foreach ($empleados as $emp): ?>
        <tr>
          <th scope="row"><?php echo $emp['id_empleado']; ?></th>
          <td><?php echo $emp['nombre'] . " " . $emp['primer_apellido']; ?></td>
          <td><?php echo $emp['rfc']; ?></td>
          <td><?php echo $emp['correo']; ?></td>
          <td><?php echo $emp['nombre_municipio']; ?></td>
          <td><?php echo $emp['nombre_negocio']; ?></td>
          <td>
              <div class="btn-group" role="group">
                <a href="empleado.php?accion=actualizar&id=<?php echo $emp['id_empleado']; ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="empleado.php?accion=borrar&id=<?php echo $emp['id_empleado']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
              </div>
          </td>
        </tr>
    <?php endforeach; ?>
     </tbody>
    </table>
</div>