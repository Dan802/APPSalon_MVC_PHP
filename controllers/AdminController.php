<?php 

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {
    
    public static function index(Router $router) {

        isAdmin();
        
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);

        if( !checkdate($fechas[1], $fechas[2], $fechas[0]) ) {
            header('Location: /404');
        }

        // Consultar la base de datos

            // 1. Columnas a extraer
            $consulta = "SELECT citas.id, citas.hora,";
            $consulta .= " CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente,";
            $consulta .= " usuarios.email, usuarios.celular, servicios.nombre as servicio, servicios.precio  ";
            $consulta .= " FROM citas  ";

            // 2. Unimos todas las tablas (ninguna columan es reescrita)
            $consulta .= " LEFT JOIN usuarios ";

            // 3. ON: especificamos la condicion
            // si citas.usuarioId = usurios.id entonces se agregan los valores al resultado
            
            // if there's no match, the row from citas is still included in the result, 
            // but columns from usuarios will contain NULL values. 
            $consulta .= " ON citas.usuarioId=usuarios.id  ";

            // Unimos las demas tablas
            $consulta .= " LEFT OUTER JOIN citaservicios ";
            $consulta .= " ON citaservicios.citaId=citas.id ";
            $consulta .= " LEFT OUTER JOIN servicios ";
            $consulta .= " ON servicios.id=citaservicios.servicioId ";
            $consulta .= " WHERE citas.fecha =  '$fecha' ";

        $todasLasCitas = AdminCita::SQL($consulta);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'], 
            'todasLasCitas' => $todasLasCitas, 
            'fecha' => $fecha
        ]);
    }
}