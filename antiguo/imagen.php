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

$imagen = 'url o dirección de la imagen';
$token = "6514974985:AAG06s2qRdtNxE0k0yhwHkZ-cbGb8Jfd-Yg";

$res ="SELECT * FROM `push_carga` WHERE imagen!='' and etiqueta!='' ";
$res = $mysqli->query("$res");
$fila = $res->fetch_all(MYSQLI_ASSOC);

foreach ($fila as  $value) {

$datos = [
    'chat_id' => $value['idtelegram'],
    #'chat_id' => '@el_canal si va dirigido a un canal',
    'photo' => $value['imagen'],
    #'photo' => $imagen,
    'caption' => $value['etiqueta']
];
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $token . "/sendPhoto");
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$r_array = json_decode(curl_exec($ch), true);

curl_close($ch);
if ($r_array['ok'] == 1) {
 //   echo "Imagen enviada.";
    $updateQuery = "UPDATE `push_carga` SET `estado` = 'Imagen enviada' WHERE `id` = " . $value['id'];
    $mysqli->query($updateQuery);

} else {
   // echo "Imagen no enviada.";
 //   print_r($r_array);
    $updateQuery = "UPDATE `push_carga` SET `estado` = 'Imagen no enviada' WHERE `id` = " . $value['id'];
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
