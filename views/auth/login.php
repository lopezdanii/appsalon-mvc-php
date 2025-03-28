<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina"> Inicia sesión con tus datos</p> 

<?php include_once __DIR__ . "/../templates/alertas.php"?>

<form class="formulario" action="/" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Tu Email" name = "email" value="<?php echo s($auth->email);?>">
    </div>
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" placeholder="Tu Contraseña" name = "password">
    </div>

    <input type="submit" class="boton" value="Iniciar sesión">

</form>

<div class="acciones">
    <a href="/password-olvidada"> ¿Olvidó su contraseña?</a>
    <a href="/crear-cuenta"> ¿No tiene cuenta? Crear una</a>
</div>