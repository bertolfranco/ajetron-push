<?php

session_start();
global $mysqli;
require_once 'dbconect.php';

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
clearstatcache();

$paisSession = $_SESSION["pais"];
$active = "coberturanncc";

// conexión

if (isset($_POST["delete"])) {
    $query = "DELETE FROM plantilla_cobertura_econored WHERE pais = '" . $paisSession . "'";
    $resultados = mysqli_query($mysqli, $query);

    $uploadDir = __DIR__ . '/../assets/cajasminimas/';

    $files = glob($uploadDir . '*'); // obtiene todos los archivos dentro de la carpeta

    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file); // elimina el archivo
        }
    }

}

if (isset($_POST['enviar'])) {
    $uploadDir = __DIR__ . '/../assets/cajasminimas/';
    $fileName = basename($_FILES['file']['name']);
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExt !== 'csv') {
        die("❌ Solo se permiten archivos CSV.");
    }
    $destPath = $uploadDir . $fileName;
    if (move_uploaded_file($fileTmp, $destPath)) {
        echo "✅ Archivo guardado correctamente: uploads/$newFileName";

        $paisSession = $mysqli->real_escape_string($paisSession);
        $fileName = $mysqli->real_escape_string($fileName);

        $q = "INSERT INTO plantilla_cobertura_econored (pais, documento) VALUES ('$paisSession', '$fileName')";

        $mysqli->query($q);
    } else {
        echo "❌ Error al subir el archivo.";
    }
}

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Comisiones Carga Plantillas</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <header>
        <!-- Fixed navbar -->
        <?php
        $username = $_SESSION["username"];
        if ($paisSession == "CO"){
            include "./comisiones_menu_co.php";
        }
        else{
            if ($username == "admin-ECONORED-CR" || $username == "admin-ECONORED") {
              include "./comisiones_menu_econored.php";
            } else {
              include "./comisiones_menu.php";
            }
        } ?>
    </header>

    <!-- Begin page content -->

    <div class="container">
        <div class="row align-items-start text-center">
            <div class="col">
                <h3 class="mt-3">Carga Plantilla Cobertura NNCC</h3>
            </div>
            <div class="col">
                <img src="../ajetron.png" alt="Imagen de encabezado" class="img-fluid mt-3" style="max-width: 150px;">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12 col-md-12">
                <!-- Contenido -->
                <div class="outer-container">
                    <form action="" method="post" name="frmExcelImport" id="frmExcelImport"
                        enctype="multipart/form-data">
                        <div>
                            <div class="row">

                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="file">Elija archivo csv</label>
                                    <input type="file" class="form-control" name="file" id="file" accept=".csv">
                                </div>
                            </div>

                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <button type="submit" id="submit" name="enviar" class="btn btn-warning">Importar
                                    Registros
                                </button>
                                <button type="submit" id="submit" name="delete" class="btn btn-warning">Borrar Registros
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="outer-container" id="response" class="<?php if (!empty($type)) {
                    echo $type . " display-block";
                } ?>"><?php if (!empty($message)) {
                     echo $message;
                 } ?>
                </div>
                <br>


                <?php
                $sqlSelect = "SELECT * FROM plantilla_cobertura_econored where pais = '" . $paisSession . "'";
                $result = mysqli_query($mysqli, $sqlSelect);

                if (mysqli_num_rows($result) > 0) {
                    ?>

                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pais</th>
                                <th>Documento</th>
                            </tr>
                        </thead>
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <tbody>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['pais']; ?></td>
                                    <td><?php echo $row['documento']; ?></td>
                                </tr>
                                <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
                <!-- Fin Contenido -->
            </div>
        </div>
        <!-- Fin row -->


    </div>
    <!-- Fin container -->
    <footer class="footer">
        <div class="container"> <span class="text-muted">
                <p>Todos los derechos reservados <a href="" target="_blank">AJEPER</a></p>
            </span></div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>