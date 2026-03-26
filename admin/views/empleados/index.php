<h1>Empleados</h1>
<a href="empleado.php?accion=crear" class="btn btn-success mb-3">Nuevo</a>

<?php if (isset($alerta)):
  require_once(__DIR__ . "/../alerta.php");
endif; ?>

<div class="table-responsive">
  <table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Fotografía</th>
        <th scope="col">Nombre</th>
        <th scope="col">Primer apellido</th>
        <th scope="col">Segundo apellido</th>
        <th scope="col">RFC</th>
        <th scope="col">Correo</th>
        <th scope="col">Opciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($empleados as $emp): ?>
        <tr>
          <th scope="row"><?php echo $emp['id_empleado']; ?></th>
          <td>
            <?php
            $foto = $emp['imagen'];

            // Si NO está vacía Y además tiene la estructura nueva (empieza con "/uploads/")
            if (!empty($foto) && strpos($foto, '/uploads/') === 0) {
              $ruta_foto = '/negocio' . $foto;
            } else {
              // Si está vacía o tiene basura vieja ("foto.jpg"), mostramos el default
              $ruta_foto = '/negocio/uploads/empleado/default.png';
            }
            ?>
            <img src="<?php echo $ruta_foto; ?>" alt="Foto de <?php echo $emp['nombre']; ?>" class="rounded-circle"
              style="width: 50px; height: 50px; object-fit: cover;">
          </td>
          <td><?php echo $emp['nombre'] ?></td>
          <td><?php echo $emp['primer_apellido']; ?></td>
          <td><?php echo $emp['segundo_apellido']; ?></td>
          <td><?php echo $emp['rfc']; ?></td>
          <td><?php echo $emp['correo']; ?></td>
          <td>
            <div class="btn-group" role="group">
              <a href="empleado.php?accion=actualizar&id=<?php echo $emp['id_empleado']; ?>"
                class="btn btn-warning btn-sm">Editar</a>
              <a href="empleado.php?accion=borrar&id=<?php echo $emp['id_empleado']; ?>"
                class="btn btn-danger btn-sm">Eliminar</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>