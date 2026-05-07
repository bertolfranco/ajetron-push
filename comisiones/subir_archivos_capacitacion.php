<?php
session_start();
require_once 'dbconect.php';
global $mysqli;

ini_set('upload_max_filesize', '600M');
ini_set('post_max_size', '600M');
ini_set('max_execution_time', '300');

// ==========================
// VALIDAR SESIÓN
// ==========================
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$paisSession = $_SESSION["pais"];
$mensaje = "";
$active = "subirarchivos";

// ==========================
// PROCESO DE SUBIDA
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $modulo = $_POST['modulo'] ?? '';

    if (!$modulo) {
        $mensaje = "Debe seleccionar un módulo";
    } elseif (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== 0) {
        $mensaje = "Error al cargar archivo";
    } else {

        $file = $_FILES['archivo'];

        // ==========================
        // VALIDACIONES
        // ==========================
        $mime = mime_content_type($file['tmp_name']);
        $size = $file['size'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $permitidos = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'video/mp4',
            'video/quicktime'
        ];

        if (!in_array($mime, $permitidos)) {
            $mensaje = "Tipo de archivo no permitido";
        } elseif ($size > 500 * 1024 * 1024) {
            $mensaje = "Archivo demasiado grande (máx 500MB)";
        } else {

            // ==========================
            // CREAR CARPETA
            // ==========================
            $baseDir = "/var/www/html/ajetron/public/archivos/$modulo/";

            if (!is_dir($baseDir)) {
                mkdir($baseDir, 0755, true);
            }

            $archivos = glob($baseDir . '*');

            foreach ($archivos as $archivoExistente) {
                if (is_file($archivoExistente)) {
                    unlink($archivoExistente);
                }
            }

        // ==========================
            // ELIMINAR REGISTROS DEL MODULO
            // ==========================
            $sqlDelete = "DELETE FROM archivos_capacitacion WHERE modulo = ?";
            $stmtDelete = mysqli_prepare($mysqli, $sqlDelete);

            mysqli_stmt_bind_param($stmtDelete, "s", $modulo);
            mysqli_stmt_execute($stmtDelete);

            // ==========================
            // GENERAR NOMBRE ÚNICO
            // ==========================
            $nombreUnico = bin2hex(random_bytes(8)) . "." . $ext;

            $rutaFisica = $baseDir . $nombreUnico;
            $rutaBD = "/archivos/$modulo/" . $nombreUnico;

            // ==========================
            // GUARDAR ARCHIVO
            // ==========================
            if (move_uploaded_file($file['tmp_name'], $rutaFisica)) {

                // ==========================
                // GUARDAR EN BD
                // ==========================
                $sql = "INSERT INTO archivos_capacitacion 
                (modulo, nombre_original, nombre_guardado, ruta, tipo_mime, extension, peso)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($mysqli, $sql);

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssssi",
                    $modulo,
                    $file['name'],
                    $nombreUnico,
                    $rutaBD,
                    $mime,
                    $ext,
                    $size
                );

                if (mysqli_stmt_execute($stmt)) {
                    $mensaje = "Archivo subido correctamente";
                } else {
                    $mensaje = "Error al guardar en BD";
                }
            } else {
                $mensaje = "Error al mover archivo";
            }
        }
    }
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Archivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <header>
        <?php
        // ==========================
        // TU MISMA LÓGICA DE MENÚ
        // ==========================
        if ($paisSession == "CO") {
            include "./comisiones_menu_co.php";
        } else {
            include "./comisiones_menu.php";
        }
        ?>
    </header>

    <div class="container mt-4">

        <div class="row align-items-center text-center">
            <div class="col">
                <h3>Subida de Archivos</h3>
            </div>
            <div class="col">
                <img src="../ajetron.png" class="img-fluid" style="max-width: 120px;">
            </div>
        </div>

        <hr>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="row">

                <div class="col-md-4">
                    <label class="form-label">Módulo</label>
                    <select name="modulo" class="form-select form-select-sm" required>
                        <option value="">Seleccione</option>
                        <option value="seguimiento">Seguimiento</option>
                        <option value="proyectos">Proyectos</option>
                        <option value="pasosventa">Pasos de la venta</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Archivo</label>
                    <input type="file" name="archivo" class="form-control form-control-sm" required>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        Subir
                    </button>
                </div>

            </div>

        </form>

    </div>

    <footer class="footer mt-5">
        <div class="container text-center">
            <span class="text-muted">
                <p>Todos los derechos reservados <a href="#">AJEPER</a></p>
            </span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>