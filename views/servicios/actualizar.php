<h1 class="nombre-pagina">Actualizar Servicios</h1>
<p class="descripcion-pagina">Modificar los valores del foemulario</p>

<?php 
    //include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form method="POST" class="formulario">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" value="Actualizar" class="boton">
</form>