<?php

//Comienzo
require_once 'dbconect.php';
global $mysqli;
global $TOKEN_BOT;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Guatemala');

if(!isset($_GET['tipo'])){
    throw new Exception('Pais requerido');
}
$tipo=$_GET['tipo'];
$token = $TOKEN_BOT;

$res ="select DISTINCT pc.*, vpb.idtelegram 
from push_carga pc 
join v_push_base vpb on pc.pais=vpb.pais
AND ((
      (pc.sucursal IS NULL OR pc.sucursal = '')
      AND (pc.zona IS NULL OR pc.zona = '')
	  AND (pc.ruta IS NULL OR pc.ruta = ''))
        OR
        ((pc.sucursal IS NOT NULL AND pc.sucursal != '' AND pc.sucursal = vpb.cod_sucursal)
            OR (pc.zona     IS NOT NULL AND pc.zona     != '' AND pc.zona = vpb.zona)
            OR (pc.ruta     IS NOT NULL AND pc.ruta     != '' AND pc.ruta = vpb.ruta)))
where pc.programacion  = '$tipo'
";

$res = $mysqli->query("$res");
$fila = $res->fetch_all(MYSQLI_ASSOC);



foreach ($fila as  $value) {

    if($value['tipo']=='imagen'){
        $datos = [
            'chat_id' => $value['idtelegram'],
            'photo' => $value['valor'],
            'caption' => $value['etiqueta']
        ];
    }elseif($value['tipo']=='texto'){
        $escapedMessage = str_replace(['.', '-', '#', '(', ')', '!'], ['\.', '\-', '\#', '\(', '\)', '\!'], $value['valor']); // Escapar los puntos con barra invertida
        $datos = [
            'chat_id' => $value['idtelegram'],
            'text' => $escapedMessage,
            'parse_mode' => 'MarkdownV2' #formato del mensaje
        ];
    }elseif($value['tipo']=='documento'){
        $datos = [
            'chat_id' => $value['idtelegram'],
            'document' => $value['valor'],
            'caption' => $value['etiqueta']
        ];
    }else{
        print_r("no cumple con tipo");
        break;
    }

    $ch = curl_init();

    if($value['tipo']=='imagen'){
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $token . "/sendPhoto");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    }elseif($value['tipo']=='texto'){
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $token . "/sendMessage");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    }elseif($value['tipo']=='documento'){
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $token . "/sendDocument");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:multipart/form-data']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    }

    $r_array = json_decode(curl_exec($ch), true);
    curl_close($ch);
    if($r_array['ok'] == 1){
        echo $value['idtelegram']," ","Campaña"," ",$value['id']," EXITOSO\n";
        $id = $value['id'];  // id de push_carga
        $update = "UPDATE push_carga SET enviado = 1 WHERE id = $id";
        $mysqli->query($update);
    }else{
        echo $value['idtelegram']," ","Campaña"," ",$value['id']," FALLO ", $r_array['error_code'], " ", $r_array['description'], "\n"  ;
    }
}

unset($value);

?>
