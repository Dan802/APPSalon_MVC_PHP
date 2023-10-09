<?php 

    /* Recorderis: $alertas = [$key [$key => $value]]
    Es decir, $alertas = ['errores' => ['error1', 'error2', 'error3']]
    O tambien, $alertas = ['existoso' => ['exito1', 'exito2', 'exito3']]*/
    
    foreach ($alertas as $key => $values):
        foreach($values as $mensaje):
?>   

    <div class="alerta <?php echo $key; ?>">
        <?php echo $mensaje; ?>
    </div>
    
<?php
        endforeach;
    endforeach;
?>