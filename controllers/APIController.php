<?php 

namespace Controllers;

use Classes\Email;
use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;
use Model\Usuario;

class APIController {
    
    public static function index() {
        $servicios = Servicio::all();
        
        //retornamos todos los servicios en .JSON para acceder a ellos a traves de JS
        echo json_encode($servicios); 
    }

    public static function guardar() {

        // Almacena la cita y devuelve el id
       $cita = new Cita($_POST);
       $resultado_And_id = $cita->guardar();

        if($resultado_And_id) {

            $usuario = new Usuario();
            $usuario = $usuario->where('id', $_POST['usuarioId']);

            if($usuario) {
                $email = new Email($usuario->email, $usuario->nombre, '', $_POST['fecha'], $_POST['hora']);
                $email->enviarInfoCita();
            }
        }

        // Almacena la cita y el servicio
        // Almacena los servicios con el id de la cita

        // Before: $idServicios {"servicios": "1,2,3,4,5..."}
        $idServicios = explode(",", $_POST['servicios']);
        // After: $idServicios {"servicios":[ "1", "2", "3", "4"...] }

        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $resultado_And_id['id'],
                'servicioId' => $idServicio
            ];
            
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

       echo json_encode(['resultado' => $resultado_And_id]);
    }

    public static function eliminar() {

        isAdmin();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            if( !filter_var($id, FILTER_VALIDATE_INT) ) {
                header('Location: /admin');
            }

            $cita = Cita::find($id);

            if(isset($cita)) {
                $cita->eliminar();
                header('Location:' . $_SERVER['HTTP_REFERER']);
            }
        }   
    }
}