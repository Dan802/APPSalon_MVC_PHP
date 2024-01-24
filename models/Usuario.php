<?php 

namespace Model;

class Usuario extends ActiveRecord {

    //Atributos de la Base de Datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'celular', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $celular;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->celular = $args['celular'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    /* Mensajes de validación para la creación de una cuenta */
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre del cliente es obligatorio';
        }
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El apellido del cliente es obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El correo electrónico es obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
        }
        if(strlen($this->celular) !== 10) {
            self::$alertas['error'][] = 'El número de celular deben ser 10 dígitos';
        }

        return self::$alertas;
    }

    /**
     * Funcion especifica para validar Usuario y contraseña
     */
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatorio';
        }
        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe tener al menos seis caracteres';
        }
        return self::$alertas;
    }

    /* Revisa si el usuario ya existe */
    public function existeUsuario () {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query); // return mysqli result

        if($resultado->num_rows) { // Si num_rows == 1, es decir, se econtró un resultado, entonces...
            self::$alertas['error'][] = 'El correo ya se encuentra registrado';
        }

        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado) {
            // El usuario no esta confirmado
            self::$alertas['error'][] = 'La contraseña es incorrecta o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }
}