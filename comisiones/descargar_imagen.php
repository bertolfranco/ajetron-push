<?php
global $mysqli;
require_once 'dbconect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ruta = $_POST['ruta'];
    $paisactual = $_POST['pais'];
    $anio = $_POST['anio'];
    $mes = $_POST['mes'];
    $urlComisionesHistorico = '/var/www/html/ajetron/public/comisiones-historicas';
    $ruta_descarga = "$urlComisionesHistorico/$anio-$mes/$paisactual/grafica_".$ruta.".png";

    if($paisactual == "CO" || $paisactual == "EC"){
        $ruta_modelo = getRutaCompletaPais($paisactual,$ruta);

        // Validar que todos los campos estén presentes
        if (empty($ruta_modelo)) {
            header('Content-Type: application/json');
            echo json_encode(['mensaje' => 'No se encuentra el modelo de la ruta.']);
            exit;
        }

        $ruta_descarga = "$urlComisionesHistorico/$anio-$mes/$ruta_modelo";
    }

    // Validar que todos los campos estén presentes
    if (empty($ruta) || empty($paisactual) || empty($anio) || empty($mes)) {
        header('Content-Type: application/json');
        echo json_encode(['mensaje' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Nombre del archivo para descargar
    $nombre_archivo = 'grafica_'.$ruta.'_'.$anio.$mes.'.png';

    if (!file_exists($ruta_descarga)) {
        header('Content-Type: application/json');
        echo json_encode(['mensaje' => 'La imagen no existe.']);
        exit;
    }

    // Tipo MIME
    header('Content-Type: image/png');

    // Forzar descarga
    header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
    header('Content-Length: ' . filesize($ruta_descarga));
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Expires: 0');

    // Enviar el archivo
    readfile($ruta_descarga);
    exit;
}

function getRutaCompletaPais($pais,$ruta){
    $var = "";
    if ($pais == 'EC') {
        $fuerza_venta = consulta_fuerza_venta_usuario($pais, $ruta);
        $cedis = consulta_cedis_usuario($pais, $ruta);

        switch($fuerza_venta) {
         case "TRADICIONAL":
              switch($cedis) {
              case "AMBATO":
              $var = "$pais/m2/grafica_".$ruta.".png";
              break;
              default:
              $var = "$pais/m1/grafica_".$ruta.".png";
              break;
              }
          break;
          case "PARALELA":
           $var = "$pais/m5/grafica_".$ruta.".png";
          break;
          case "ABARROTES":
           $var = "$pais/m5/grafica_".$ruta.".png";
          break;
          case "BIG":
           $var = "$pais/m6/grafica_".$ruta.".png";
          break;
         default:
               $var = "$pais/m4/grafica_".$ruta.".png";
         break;
        }


        }else {
             if ($pais == 'CO'){
                $modelo_co = consulta_modelo_comisional($pais, $ruta);
                switch($modelo_co){
                    case "m1":
                        $var = "$pais/m1/grafica_".$ruta.".png";
                        break;
                    case "m2":
                        $var = "$pais/m2/grafica_".$ruta.".png";
                    break;
                    default:
                        $var = "$pais/m3/grafica_".$ruta.".png";
                    break;
                }

             } else if ($pais == 'TH') {
                $var = "$pais/m1/grafica_".$ruta.".png";
             }
            else {
                $var = "$pais/grafica_".$ruta.".png";
            }
        }
    return $var;
}

function consulta_fuerza_venta_usuario($pais,$ruta){
  global $mysqli;

  $resultado = $mysqli->query("SELECT trim(`fuerza_venta`) as fuerza_venta FROM `v_usuarios` WHERE `pais` = '$pais' and  `cod_ruta` = '$ruta'");

  $stock = mysqli_fetch_assoc($resultado);
  return isset($stock['fuerza_venta'])? $stock['fuerza_venta'] : "";
}

function consulta_cedis_usuario($pais,$ruta){
  global $mysqli;

  $resultado = $mysqli->query("SELECT trim(`cedis`) as cedis FROM `v_usuarios` WHERE `pais` = '$pais' and  `cod_ruta` = '$ruta'");

  $stock = mysqli_fetch_assoc($resultado);
  return isset($stock['cedis'])? $stock['cedis'] : "";
}

function consulta_modelo_comisional($pais,$ruta){
 global $mysqli;

 $resultado = $mysqli->query("SELECT trim(`modelo`) as modelo FROM `modelos_compensacion` WHERE `pais` = '$pais' and  `ruta` = '$ruta'");

 $stock = mysqli_fetch_assoc($resultado);
 return isset($stock['modelo'])? $stock['modelo'] : "";
}



?>