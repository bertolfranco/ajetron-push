<?php
// 🔥 EVITA CUALQUIER SALIDA
error_reporting(0);
ini_set('display_errors', 0);

session_start();

// 🔥 LIMPIAR TODO BUFFER POSIBLE
while (ob_get_level()) {
    ob_end_clean();
}

// 🔥 IMPORTANTE: NO usar require que imprima cosas
require_once 'dbconect.php';
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Validar sesión
if (!isset($_SESSION["username"])) {
    exit;
}

$paisSession = $_SESSION["pais"];
$paisSafe = $mysqli->real_escape_string($paisSession);

$query = "SELECT * 
          FROM v_comisiones_cob_econored_hn 
          WHERE pais = '$paisSafe'";

$result = mysqli_query($mysqli, $query);

if (!$result) {
    exit;
}

// Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Cabeceras
$fields = mysqli_fetch_fields($result);
$col = 1;

foreach ($fields as $field) {
    $sheet->setCellValueByColumnAndRow($col, 1, $field->name);
    $col++;
}

// Datos
$rowNum = 2;
while ($row = mysqli_fetch_assoc($result)) {
    $col = 1;
    foreach ($row as $value) {
        $sheet->setCellValueByColumnAndRow($col, $rowNum, $value);
        $col++;
    }
    $rowNum++;
}

// 🔥 LIMPIEZA FINAL ANTES DE OUTPUT
while (ob_get_level()) {
    ob_end_clean();
}

// Headers
$filename = "reporte.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;