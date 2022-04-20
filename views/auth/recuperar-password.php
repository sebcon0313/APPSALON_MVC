<h1 class="nombre-pagina">Restablecer Password</h1>
<p class="descripcion-pagina">Ingresa tu nueva Contraseña</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>


<?php if ($error) return ; ?>
<form method="POST" class="formulario">

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Nuevo Password">
    </div>
    <div class="campo">
        <label for="password2">Password</label>
        <input type="password" name="password2" id="password2" placeholder="Confirma tu Password">
    </div>

    <input type="submit" class="boton" value="Restablecer Password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesion</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crea Una</a>
</div>