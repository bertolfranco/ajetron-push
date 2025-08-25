<?php

include '/var/www/config/config.dev.php';
global $HOST, $DB_USER, $DB_PASSWORD, $DB_AJETRON;

$mysqli = new mysqli($HOST, $DB_USER, $DB_PASSWORD, $DB_AJETRON);

if ($mysqli->connect_error) {
    die("Error de conexión a la base de datos: " . $mysqli->connect_error);
}

?>