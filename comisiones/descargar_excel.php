<?php
session_start();

require_once 'dbconect.php';
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 🔐 Validar sesión
if (!isset($_SESSION["username"])) {
    exit("No autorizado");
}

$paisSession = $_SESSION["pais"];
$paisSafe = $mysqli->real_escape_string($paisSession);

// 🔥 LIMPIEZA TOTAL
while (ob_get_level()) {
    ob_end_clean();
}

$query = "SELECT * 
          FROM v_comisiones_cob_econored_hn 
          WHERE pais = '$paisSafe'";

$result = mysqli_query($mysqli, $query);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($mysqli));
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

$sheet->getStyle('1:1')->getFont()->setBold(true);

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

// Archivo
$filename = "cobertura_econored_" . $paisSession . "_" . date("Ymd_His") . ".xlsx";

// Headers LIMPIOS
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;