<?php

include '/var/www/config/env.php';
global $HOST, $DB_USER, $DB_PASSWORD, $DB_AJETRON;

// Inicializar mysqli
$mysqli = mysqli_init();

// Activar SSL (sin certificados explícitos)
mysqli_ssl_set($mysqli, NULL, NULL, NULL, NULL, NULL);

// Conectar usando SSL
mysqli_real_connect(
    $mysqli,
    $HOST,
    $DB_USER,
    $DB_PASSWORD,
    $DB_AJETRON,
    3306,
    NULL,
    MYSQLI_CLIENT_SSL
);

// Validar conexión
if ($mysqli->connect_errno) {
    die("Error de conexión a la base de datos: " . $mysqli->connect_error);
}

?>
