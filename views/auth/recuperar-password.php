<h1 class="nombre-pagina">Recuperar la Contraseña</h1>

<?php 
    include_once __DIR__ . '/../templates/alertas.php';

   // Evita que el codigo se ejecute si $error es true 
   if($error):
?>
    <div class="acciones"> <a href="/">Volver a la página principal</a> </div>
<?php
        return;
    endif;
?>

<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<form method="POST" class="formulario">

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu nueva contraseña">
    </div>

    <input type="submit" class="boton" value="Guardar Nueva Contraseña">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inciar Sesión</a>
</div>