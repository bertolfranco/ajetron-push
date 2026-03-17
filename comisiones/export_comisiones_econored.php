<?php
session_start();
require_once 'dbconect.php';

// 🔐 Validar sesión
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$paisSession = $_SESSION["pais"];
$username = $_SESSION["username"];
$active = "exportcomisiones";
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Descargar Cobertura Econored</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<header>
<?php
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

    <!-- 🔥 CAMBIO AQUÍ -->
    <a href="descargar_excel.php" class="btn btn-success btn-lg">
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