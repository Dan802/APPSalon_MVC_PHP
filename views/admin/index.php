<h1 class="nombre-pagina">Pagina de Administración</h1>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php 

    if(count($todasLasCitas) === 0) {
        echo "<h3>No hay citas registradas para el día $fecha </h3>";
    }

?>

<div id="citas-admin">

    <ul class="citas">

        <?php 
        
            $idCita = 0;
            
            foreach( $todasLasCitas as $key => $cita ): 

                if($idCita !== $cita->id):
                    $totalCita = 0; // Esto solo se ejecuta una vez
                    datosUsuarioCita($cita);
                    $idCita = $cita->id;
                endif; 

                $totalCita += $cita->precio;
        
                mostrarServicio($cita);

                $servicioActual = $cita->id;
                $servicioProximo = $todasLasCitas[$key + 1]->id ?? 0;

                if(esUltimo($servicioActual, $servicioProximo)) :
                    mostrarTotal($totalCita, $cita);
                endif;

                
            
            endforeach; ?>
    </ul>
</div>

<?php 
    function datosUsuarioCita($cita) {?>
         <li>
            <p>ID Cita: <span><?php echo $cita->id; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>
            <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
            <p>Email: <span><?php echo $cita->email; ?></span></p>
            <p>Celular: <span><?php echo $cita->celular; ?></span></p>
            <h3>Servicios</h3>
    <?php } 

    function mostrarServicio($cita) {?>
        <p class="servicio"><?php echo $cita->servicio . " ". $cita->precio; ?></p>
    <?php }

    function mostrarTotal($totalCita, $cita) {?>
        <p class="total">Total: <span>$<?php echo $totalCita; ?></span></p>

        <form action="/api/eliminar" method="POST"> 
            <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
            <input type="submit" class="boton-eliminar" value="Eliminar">
        </form>
        
    <?php } 
?>

<?php 
    // vble de layout.php
    $script = "<script src='build/js/buscador.js'></script>"
?>