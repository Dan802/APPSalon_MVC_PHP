<?php

function debuguearConExit($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function debuguearSinExit($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
}

// Escapa / Sanitiza el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $actual, string $proximo): bool {
    if( $actual !== $proximo) {
        return true;
    } else {
        return false;
    }
}

/**
 * - Revisa si el usuario esta autenticado por medio de $_SESSION['login'] 
 * - $_SESSION es modificada en LoginController
 */
function isAuth() : void {

    // Si no se esta autenticado $_SESSION = array(0){};
    // $_SESSION['login'] =  Undefined array key "login" || null

    if(isset($_SESSION['login'])) {
        // Si estamos autenticados isset dará true
    } else {
        // Si no estamos autenticado $_SESSION['login'] =  null / Undefined array key "login",
        // es decir, el isset daria false
        header('Location: /');
    }
}

/**
 * Si no es admin se manda a la pagina principal 
*/
function isAdmin() {
    if(!isset($_SESSION['admin'])) {
        header('Location: /cita');
    }
}