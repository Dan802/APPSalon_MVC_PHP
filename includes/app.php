<?php 

use Model\ActiveRecord;

require __DIR__ . '/../vendor/autoload.php';

// Variables de entorno encriptadas o algo así xd 
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require 'funciones.php';
require 'database.php';

// Conectarnos a la base de datos
ActiveRecord::setDB($db);   //el $db sale de database.php