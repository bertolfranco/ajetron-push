<?php
// Conexión a la base de datos
global $mysqli;
require_once 'dbconect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Guatemala');

$token = "6147191063:AAEhIaWWsvylc4lcO7WxB2HN1MNRjojdD4Q";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenido = $_POST['contenido'];
    $localidad = $_POST['localidad'];

    // Escapar puntos y enlaces
    $escapedContenido = addslashes($contenido);
    $formattedContenido = preg_replace(
        '/(https?:\/\/\S+)/',
        '[$1]($1\\)',
        $escapedContenido
    );

    $res = "SELECT * FROM `demopush` WHERE localidad = '$localidad'";
    $res = $mysqli->query($res);
    $fila = $res->fetch_all(MYSQLI_ASSOC);

    foreach ($fila as $value) {
        $datos = [
            'chat_id' => $value['idtelegram'],
            'text' => $formattedContenido,
            'parse_mode' => 'MarkdownV2'
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
            echo "Mensaje enviado.";
        } else {
            echo "Mensaje no enviado.";
            print_r($r_array);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Push Notifications</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <form id="pushForm">
      <div class="form-group">
        <label for="contenido">Contenido del mensaje:</label>
        <textarea class="form-control" id="contenido" name="contenido" rows="4"></textarea>
      </div>
      <div class="form-group">
        <label for="localidad">Selecciona una localidad:</label>
        <select class="form-control" id="localidad" name="localidad">
          <option value="guayaquil">Guayaquil</option>
          <option value="quito">Quito</option>
          <option value="manta">Manta</option>
          <option value="machala">Machala</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Enviar notificación</button>
    </form>
  </div>

  <!-- JavaScript -->
  <script>
    document.getElementById("pushForm").addEventListener("submit", function(event) {
      event.preventDefault();

      const contenido = document.getElementById("contenido").value;
      const localidad = document.getElementById("localidad").value;

      // Escapar puntos y enlaces
      const escapedContenido = contenido.replace(/\./g, '\\.');
      const formattedContenido = escapedContenido.replace(
        /(https?:\/\/\S+)/g,
        '[$1]($1\\)'
      );

      // Aquí realiza la acción de enviar el contenido y localidad al servidor PHP
      // Puedes usar AJAX, Fetch API u otros métodos para enviar datos al servidor

      console.log("Contenido formateado:", formattedContenido);
      console.log("Localidad seleccionada:", localidad);

      // Recargar la página después de enviar la notificación
      setTimeout(function() {
        location.reload();
      }, 1000); // Esperar 1 segundo antes de recargar la página

    });
  </script>
</body>
</html>
