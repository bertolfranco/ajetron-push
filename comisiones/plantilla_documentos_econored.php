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
$active = "documentoseconored";

// conexión

if (isset($_POST["delete"])) {
    $query = "DELETE FROM documentos_push WHERE pais = '" . $paisSession . "'";
    $resultados = mysqli_query($mysqli, $query);

}

if (isset($_POST['enviar'])) {
    $uploadDir = __DIR__ . '/../assets/documentos/';
    $fileName = basename($_FILES['file']['name']);
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExt !== 'pdf') {
        echo("❌ Solo se permiten archivos PDFS.");
    }
    $destPath = $uploadDir . $fileName;
    if (move_uploaded_file($fileTmp, $destPath)) {
        echo "✅ Archivo guardado correctamente: uploads/$newFileName";

         $q = "INSERT INTO documentos_push (pais, nombre, url) VALUES (
                $paisSession,
                $fileName,
                $destPath
               )";

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
    <title>Documentos Econored</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <header>
        <!-- Fixed navbar -->
        <?php
        if ($paisSession == "CO") {
            include "./comisiones_menu_co.php";
        } else {
            include "./comisiones_menu.php";
        } ?>
    </header>

    <!-- Begin page content -->

    <div class="container">
        <div class="row align-items-start text-center">
            <div class="col">
                <h3 class="mt-3">Carga Plantilla Documentos Push</h3>
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

                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Descargar Plantillas
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="static/1_plantilla_celula.csv">Celula</a>
                                        </li>
                                        <li><a class="dropdown-item" href="static/2_plantilla_banda.csv">Banda</a></li>
                                        <li><a class="dropdown-item" href="static/8_plantilla_hitrate.csv">Hit Rate</a>
                                        </li>
                                        <li><a class="dropdown-item" href="static/3_plantilla_familias.csv">Familias</a>
                                        </li>
                                        <li><a class="dropdown-item" href="static/4_plantilla_tipo_comision.csv">Tipo
                                                Comision</a></li>
                                        <li><a class="dropdown-item" href="static/5_plantilla_foco.csv">Foco</a></li>
                                        <li><a class="dropdown-item" href="static/gt_cobertura_cliente_objetivo.csv">GT
                                                - Cobertura</a></li>
                                    </ul>
                                </div>
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
                $sqlSelect = "SELECT * FROM documentos_push where pais = '" . $paisSession . "'";
                $result = mysqli_query($mysqli, $sqlSelect);

                if (mysqli_num_rows($result) > 0) {
                    ?>

                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>Pais</th>
                                <th>Nombre documento</th>
                                <th>Url</th>
                            </tr>
                        </thead>
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <tbody>
                                <tr>
                                    <td><?php echo $row['pais']; ?></td>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo $row['url']; ?></td>
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