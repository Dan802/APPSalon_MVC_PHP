<?php 
namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController {
    
    public static function login(Router $router) {

        $alertas = [];
        $auth = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                // Comprobar que exista el usuario
                // retorna los datos de 1 Usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {

                    // Verificar la contraseña y si la cuenta esta confirmada
                    // Retorna true si la contraseña es correcta y la esta confirmado
                    if( $usuario->comprobarPasswordAndVerificado($auth->password)) {

                        // Autenticar al usuario
                        // session_start(); Ya la iniciamos en comprobar rutas Router.php

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if($usuario->admin == "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'El usuario no ha sido encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas, 
            'auth' => $auth
        ]);
    }

    public static function logout() {
        
        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router) {
        
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {

                // Verificamos que el correo lo tenemos registrado
                $usuario = $auth->where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1") {
                    // El usuario si existe y la cuenta si esta confirmada

                    // Generar un nuevo token
                    $usuario->crearToken();
                    $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones(); 

                    Usuario::setAlerta('exito', 'Revisa tu correo electrónico para recuperar tu contraseña');

                } else {
                    // El usuario no existe o la cuenta no esta confirmada

                    Usuario::setAlerta('error', 'El Usuario no existe o la cuenta aún no ha sido confirmada');
                    $alertas = Usuario::getAlertas();
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {

        $alertas = [];
        $error = false;

        if(isset($_GET['token']))
        {
            $token = s($_GET['token']);

            // Buscar usuario por su token
            // Nos retorna Null si no encuentra el token en la BD
            $usuario = Usuario::where('token', $token);

            if(empty($usuario)) {   //Si no obtenemos un usuario entonces...
                Usuario::setAlerta('error', 'El Token no es válido');
                $error = true;
            }

            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                // Leer el nuevo password y guardarlo
                $password = new Usuario($_POST);
                $alertas = $password->validarPassword();

                if(empty($alertas)) {
                    $usuario->password = $password->password;
                    $usuario->hashPassword();
                    $usuario->token = null; //Importante: Siempre eliminar el token ya que es de un solo uso

                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header('Location: /');
                    }
                }
            }
        }
        else {
            Usuario::setAlerta('error', 'La URL no vino con Token :(');
            Usuario::setAlerta('error', 'Si crees que se trata de un error hablar a soporte.');
            $error = true;
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {
        
        $usuario = new Usuario(); // Nueva instancia de usuario vacio
        $alertas = []; // Arreglo de alertas vacio

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $usuario->sincronizar($_POST);
            /* Esto es lo que hace sincronizar:
                
            if existe $key y no es null, entonces...
            $usuario->key = $_POST['key']; 
            
            Aclaración: $_POST['key'] = value */
            
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {   //Revisar que las alertas este vacio

                // Verificar que el usuario no este registrado previamente
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }
                else {
                    
                    $usuario->hashPassword();   // Hasheamos el password
                    $usuario->crearToken();     // Generar un token unico

                    // Enviar el Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion(); 

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    // Si el guardado es exitoso retorna $resultado['resultado'] y también $resultado['id']
                    
                    if($resultado['resultado']) {
                        header('location: /mensaje');
                    }
                }
            }
        }
        
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje', []);
    }

    public static function confirmar(Router $router) {

        $alertas = [];

        $token = s($_GET['token']); // Obtenemos el token de la URL

        // Devuelve el arreglo de 1 usuario con el token especificado
        $usuario = Usuario::where('token', $token); 

        if(empty($usuario)) {
            // El token no es válido, es decir, no se encontró en la BD o fue modificado por el usuario
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'El token no es válido');
        } else {
            // El token es valido entonces modificar al usuario para confirmarlo
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        $alertas = Usuario::getAlertas();   //Obtener alertas

        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
?>