<?php

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

// me conecto a db
global $mysqli;
require_once 'dbconect.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Guatemala');

$token = "6514974985:AAG06s2qRdtNxE0k0yhwHkZ-cbGb8Jfd-Yg";
#$array = array('960975689', '960975689');

$res ="SELECT * FROM `push_carga` WHERE message!=''";
$res = $mysqli->query("$res");
$fila = $res->fetch_all(MYSQLI_ASSOC);

foreach ($fila as  $value) {
    $escapedMessage = str_replace(['.', '-', '#', '(', ')', '!'], ['\.', '\-', '\#', '\(', '\)', '\!'], $value['message']); // Escapar los puntos con barra invertida

    $datos = [
        'chat_id' => $value['idtelegram'],
        #'chat_id' => 'Aquí va el id númerico del destinatario',
        #'chat_id' => '@el_canal si va dirigido a un canal',
        #'text' => 'El mensaje con *formato* que el bot va a enviar, los puntos van escapados con barra invertida\. Un enlace a un [sitio](https://www.sitio.com/)\.',
        #'text' => $value['message'],
        'text' => $escapedMessage,
        'parse_mode' => 'MarkdownV2' #formato del mensaje
    ];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $token . "/sendMessage");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $r_array = json_decode(curl_exec($ch), true);

    curl_close($ch);
    if ($r_array['ok'] == 1) {
     //   echo "Mensaje enviado.";
                // Actualizar el campo 'estado' con 'Mensaje enviado' en la fila actual
                $updateQuery = "UPDATE `push_carga` SET `estado` = 'Mensaje enviado' WHERE `id` = " . $value['id'];
                $mysqli->query($updateQuery);
    } else {
     //   echo "Mensaje no enviado.";
     //   print_r($r_array);
                // Actualizar el campo 'estado' con 'Mensaje no enviado' en la fila actual
                $updateQuery = "UPDATE `push_carga` SET `estado` = 'Mensaje no enviado' WHERE `id` = " . $value['id'];
                $mysqli->query($updateQuery);
    }

}

unset($value);

// Agregar un script de JavaScript para mostrar un alert y redireccionar
echo "<script>";
echo "alert('Proceso terminado.');";
echo "window.location.href = 'carga.php';";
echo "</script>";

?>
