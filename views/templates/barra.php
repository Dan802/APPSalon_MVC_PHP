<div class="barra">
    <p>Bienvenido de nuevo <?php echo $nombre ?? ''; ?></p>

    <a class="boton" href="/logout">Cerrar Sesión</a>
</div>

<?php 
    if(!isset($_SESSION['admin'])) {
        return;
    }
?>

<div class="barra-servicios">
    <a class="boton" href="/admin">Ver Citas</a>
    <a class="boton" href="/servicios">Ver Servicios</a>
    <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
</div>