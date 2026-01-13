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
$active = "usuarios";
// conexión

if (isset($_POST['descargar'])) {


        // 2. Consulta
        $sql = "SELECT * FROM v_usuarios where pais = '".$paisSession."'";
        $result = mysqli_query($mysqli, $sql);

        // 3. Cabeceras para forzar descarga CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=usuarios.csv');

        // 4. Abrir salida
        $output = fopen('php://output', 'w');

        // 5. Escribir encabezados (nombres de columnas)
        if (mysqli_num_rows($result) > 0) {
            $fields = $result->fetch_fields();
            $headers = [];

            foreach ($fields as $field) {
                $headers[] = $field->name;
            }

            fputcsv($output, $headers, ';');
        }

        // 6. Escribir filas
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row, ';');
        }

        fclose($output);
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
        if ($paisSession == "CO"){
            include "./comisiones_menu_co.php";
        }
        else{
            include "./comisiones_menu.php";
        } ?>
</header>

<!-- Begin page content -->

<div class="container">
    <div class="row align-items-start text-center">
        <div class="col">
            <h3 class="mt-3">Usuarios Ajetron</h3>
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
                <form action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                    <div>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="submit" id="submit" name="descargar" class="btn btn-warning">Descargar usuarios
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
            $sqlSelect = "SELECT * FROM v_usuarios where pais = '".$paisSession."'";
            $result = mysqli_query($mysqli, $sqlSelect);

            if (mysqli_num_rows($result) > 0) {
                ?>

                <table class='table table-bordered'>
                    <thead>
                    <tr>
                        <th>Pais</th>
                        <th>Ruta</th>
                        <th>Vendedor</th>
                        <th>Clave</th>
                    </tr>
                    </thead>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tbody>
                    <tr>
                        <td><?php echo $row['pais']; ?></td>
                        <td><?php echo $row['cod_ruta']; ?></td>
                        <td><?php echo $row['vendedor']; ?></td>
                        <td><?php echo $row['clave']; ?></td>
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
