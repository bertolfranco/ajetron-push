<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ruta = $_POST['ruta'];
    $paisactual = $_POST['pais'];
    $anio = $_POST['anio'];
    $mes = $_POST['mes'];
    $urlComisionesHistorico = '/var/www/html/ajetron/public/comisiones-historicas';
    $ruta_descarga = "$urlComisionesHistorico/$anio-$mes/$paisactual/grafica_".$ruta.".png";

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

?>