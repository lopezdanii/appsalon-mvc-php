<h1 class="nombre-pagina"> ¿Olvidaste la Contraseña?</h1>
<p class="descripcion-pagina"> Reestablece tu contraseña escribiendo tu email</p>

<?php include_once __DIR__ . "/../templates/alertas.php"?>

<form class="formulario"  method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder= "Tu email">
    </div>

    <input type="submit" class="boton" value="Enviar instrucciones">

</form>

<div class="acciones">
    <a href="/crear-cuenta"> ¿No tienes cuenta? Crear una</a>
    <a href="/"> ¿Ya tienes una cuenta? Iniciar sesión</a>
</div>