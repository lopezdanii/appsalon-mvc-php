<h1 class="nombre-pagina">Nuevo Servicio</h1>
<p class="descripcion-pagina">Introduce los datos del nuevo servicio</p>

<?php 
    //include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form action="/servicios/crear" method="POST" class="formulario">
    <?php include_once __DIR__ . '/formulario.php'?>
    <div class="acciones">
        <input type="submit" class="boton" value="Guardar servicio">
        <a class="boton" href="/servicios">Atrás</a>  
    </div>
 
</form>

