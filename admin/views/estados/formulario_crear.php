<h1>Nuevo estado</h1>
<form action="estado.php?accion=crear" method="POST">
    <label for="">Nombre del estado:</label>>
    <input type="text" name='estado' value="<?php echo isset($_GET['estado']) ? $_GET['estado'] : ''; ?>">
    <input type="submit" name='enviar' value="Guardar">
</form>