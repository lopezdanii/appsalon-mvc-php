<h1 class="nombre-pagina"> Restablecer Contraseña</h1>
<p class="descripcion-pagina"> Introduzca una nueva contraseña</p>

<?php include_once __DIR__ . "/../templates/alertas.php"?>

<?php if($error) return;?>
<form class="formulario"  method="POST">
    <div class="campo">
        <label for="password"> Contraseña</label>
        <input type="password" id="password" placeholder="Escribe una nueva contraseña" name = "password">
    </div>

    <input type="submit" class="boton" value="Restablecer contraseña">

</form>


<div class="acciones">
    <a href="/"> Iniciar sesión</a>
    <a href="/crear-cuenta"> ¿No tienes cuenta? Crear una</a>
</div>