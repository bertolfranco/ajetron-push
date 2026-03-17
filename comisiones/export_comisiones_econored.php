<?php
session_start();
require_once 'dbconect.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 🔐 Validar sesión
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$paisSession = $_SESSION["pais"];
$username = $_SESSION["username"];
$active = "exportcomisiones";

$paisSafe = $mysqli->real_escape_string($paisSession);

// 📥 Descargar Excel
if (isset($_GET['download'])) {

    $query = "SELECT * 
              FROM v_comisiones_cob_econored_hn 
              WHERE pais = '$paisSafe'";

    $result = mysqli_query($mysqli, $query);

    if (!$result) {
        die("Error en la consulta: " . mysqli_error($mysqli));
    }

    // 📊 Crear Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Cabeceras
    $fields = mysqli_fetch_fields($result);
    $col = 1;

    foreach ($fields as $field) {
        $sheet->setCellValueByColumnAndRow($col, 1, $field->name);
        $col++;
    }

    // 🔥 Encabezado en negrita
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

    // 📁 Nombre archivo
    $filename = "cobertura_econored_" . $paisSession . "_" . date("Ymd_His") . ".xlsx";

    // 📤 Headers
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=$filename");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Descargar Cobertura Econored</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<header>
<?php
// 🔹 Menú dinámico
if ($paisSession == "CO") {
    include "./comisiones_menu_co.php";
} else {
    if (
        $username == "admin-ECONORED-CR" ||
        $username == "admin-ECONORED-GT" ||
        $username == "admin-ECONORED" ||
        $username == "admin-ECONORED-HN"
    ) {
        include "./comisiones_menu_econored.php";
    } else {
        include "./comisiones_menu.php";
    }
}
?>
</header>

<div class="container mt-5 text-center">

    <div class="row align-items-center">
        <div class="col">
            <h3>Descargar Cobertura Econored</h3>
            <p>País: <?php echo htmlspecialchars($paisSession); ?></p>
        </div>
        <div class="col">
            <img src="../ajetron.png" class="img-fluid" style="max-width: 120px;">
        </div>
    </div>

    <hr>

    <a href="?download=1" class="btn btn-success btn-lg">
        Descargar Excel
    </a>

</div>

<footer class="footer mt-5">
    <div class="container text-center">
        <span class="text-muted">
            <p>Todos los derechos reservados <a href="#">AJEPER</a></p>
        </span>
    </div>
</footer>

</body>
</html>