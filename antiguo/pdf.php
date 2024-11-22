<?php

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

global $mysqli;
require_once 'dbconect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Guatemala');

$archivo = 'el nombre del documento.pdf';
$token = "6514974985:AAG06s2qRdtNxE0k0yhwHkZ-cbGb8Jfd-Yg";

$res ="SELECT * FROM `push_carga` WHERE pdf!='' and etiquetapdf!='' ";
$res = $mysqli->query("$res");
$fila = $res->fetch_all(MYSQLI_ASSOC);

foreach ($fila as  $value) {

    $documento = new CURLFile(realpath($archivo));
    $documento->setPostFilename($archivo);

    $datos = [
        'chat_id' => $value['idtelegram'],
        #'chat_id' => '@el_canal si va dirigido a un canal',
        'document' => $value['pdf'],
        'caption' => $value['etiquetapdf']
    ];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $token . "/sendDocument");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:multipart/form-data']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $r_array = json_decode(curl_exec($ch), true);

    curl_close($ch);
    if ($r_array['ok'] == 1) {
  //      echo "Documento enviado.";
        $updateQuery = "UPDATE `push_carga` SET `estado` = 'Documento enviado' WHERE `id` = " . $value['id'];
        $mysqli->query($updateQuery);

    } else {
//        echo "Documento no enviado.";
//        print_r($r_array);
        $updateQuery = "UPDATE `push_carga` SET `estado` = 'Documento no enviado' WHERE `id` = " . $value['id'];
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
