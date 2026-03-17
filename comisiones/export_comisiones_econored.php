<?php
session_start();
require_once 'dbconect.php';

// 🔐 Validar sesión
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$paisSession = $_SESSION["pais"];
$active = "exportcomisiones";
$paisSafe = $mysqli->real_escape_string($paisSession);

// 📥 Si se solicita descarga
if (isset($_GET['download'])) {

    $query = "SELECT * 
              FROM v_comisiones_cob_econored_hn 
              WHERE pais = '$paisSafe'";

    $result = mysqli_query($mysqli, $query);

    if (!$result) {
        die("Error en la consulta: " . mysqli_error($mysqli));
    }

    $filename = "cobertura_econored_" . $paisSession . "_" . date("Ymd_His") . ".csv";

    // 📤 Headers para Excel
    header("Content-Type: text/csv; charset=UTF-8");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    // BOM UTF-8 (acentos correctos)
    echo "\xEF\xBB\xBF";

    $output = fopen("php://output", "w");

    // Cabeceras
    $fields = mysqli_fetch_fields($result);
    $headers = [];
    foreach ($fields as $field) {
        $headers[] = $field->name;
    }
    fputcsv($output, $headers);

    // Datos
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Descargar Cobertura Econored</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5 text-center">
    <h3>Descargar Cobertura Econored</h3>
    <p>País: <?php echo htmlspecialchars($paisSession); ?></p>

    <a href="?download=1" class="btn btn-success btn-lg">
        Descargar Excel
    </a>
</div>

</body>
</html>