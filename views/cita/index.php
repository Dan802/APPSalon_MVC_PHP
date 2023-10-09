<h1 class="nombre-pagina">Crear una nueva Cita</h1>
<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<div id="app">

    <nav class="tabs">
        <!-- data-paso: atributo personalizado -->
        <button type="button" data-paso="1" >Servicios</button>
        <button type="button" data-paso="2">Información Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div id="paso-1" class="seccion mostrar">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>
    <div id="paso-2" class="seccion">
        <h2>Tus Datos y Citas</h2>
        <p class="text-center">Coloca tus datos y fecha de tu cita</p>

        <form class="formulario">
            <div class="campo ">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Tu Nombre"
                value="<?php echo $nombre;?>" disabled>
            </div>
            <div class="campo">
                <label for="fecha">Fecha</label>
                    <?php 
                        //Método largo, si solo ponemos date() arroja el dia en UTC-0, es decir,
                        // Si en Colombia son las 10:00 pm y alguien quiere sacar cita, 
                        // al usar min = "date('Y-m-d', strtotime('+1 day')" serían dos dias extras despues 

                        date_default_timezone_set('America/Bogota');

                        $dateInfo = getdate();
                        $day = $dateInfo['mday'];
                        $month = str_pad($dateInfo['mon'], 2, '0', STR_PAD_LEFT); // Format month with leading zeros
                        $year = $dateInfo['year'];

                        // Calculate the next day
                        $nextDay = $day + 1;

                        // Check if the next day exceeds the number of days in the current month
                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        if ($nextDay > $daysInMonth) {
                            // If the next day exceeds the current month's days, increment the month and set the day to 1
                            $nextDay = 1;
                            $month++;
                            if ($month > 12) {
                                // If the next month exceeds 12, increment the year and set the month to 1
                                $month = 1;
                                $year++;
                            }
                        }

                        $nextDayFormatted = str_pad($nextDay, 2, '0', STR_PAD_LEFT);
                    ?>
                <input type="date" id="fecha" min="<?php 
                    // echo date('Y-m-d', strtotime('+1 day')); 
                    echo "$year-$month-$nextDayFormatted"; ?>">
            </div>
         
            <div class="campo">
                <label for="hora">Hora</label>
                <input type="time" id="hora" min="10:00" max="18:00">
            </div>
            <input type="hidden" id="id" value="<?php echo s($id);?>">
        </form>
    </div>
    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la información sea correcta</p>
    </div>

    <div class="paginacion">
        <button class="boton" id="anterior">&laquo; Anterior</button>
        <button class="boton" id="siguiente">Siguiente &raquo;</button>
    </div>
    
</div>

<?php 

$script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='/build/js/app.js'></script>
";
?>

