<?php

session_start();
global $mysqli;
require_once 'dbconect.php';

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
clearstatcache();

// conexión

if (isset($_POST["delete"])) {
    $query = "TRUNCATE push_carga";
    $resultados = mysqli_query($mysqli, $query);

}

if (isset($_POST["eliminar"])) {

    $id = $_POST["eliminar"];
    $query = "DELETE FROM push_carga WHERE id=$id";
    $resultados = mysqli_query($mysqli, $query);

}

if (isset($_POST['enviar'])) {

    $filename = $_FILES["file"]["name"];
    $info = new SplFileInfo($filename);
    $extension = pathinfo($info->getFilename(), PATHINFO_EXTENSION);

    if ($extension == 'csv') {
        $filename = $_FILES['file']['tmp_name'];
        $handle = fopen($filename, "r");
        $firstRowSkipped = false; // Añadir esta línea

        // Obtener el delimitador seleccionado por el usuario
        $selectedDelimiter = $_POST['delimiter'];

//  $handle = str_replace(',',';',$handle);
        while (($data = fgetcsv($handle, 100000, $selectedDelimiter)) !== FALSE) {

            if (!$firstRowSkipped) {
                $firstRowSkipped = true;
                continue; // Saltar la primera fila
            }

            $q = "INSERT INTO push_carga (pais, descripcion, tipo, valor, etiqueta, programacion) VALUES (
		'$data[0]',
		'$data[1]',
        '$data[2]',
        '$data[3]',
        '$data[4]',
        '$data[5]'
	    )";

            $mysqli->query($q);
        }
        fclose($handle);
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
    <title>Importar archivo de csv al servidor - GTM</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AJEPER</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="base.php">
                            BASE
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link"
                        <?php
                            $paisSession = $_SESSION["pais"];
                            if ($paisSession == "CO"){
                                echo 'href="comisiones/comisiones_modelo_compensacion.php"';
                            }
                            else
                            {
                                echo 'href="comisiones/comisiones_celula.php"';
                            }
                        ?>
                        >
                            Comisiones
                        </a>
                    </li>
					<?php
					$username = $_SESSION["username"];
					if ($username == "admin-RL"){
					 $menu = '<li class="nav-item active"><a class="nav-link" href="cargarutalovers.php">Ruta Lovers</a></li>';
					 echo $menu;
					}
					?>
                    <!-- Agrega el nuevo enlace para descargar el archivo -->
                    <li class="nav-item">
                        <a class="nav-link" href="assets/ajetronpush.csv" download>Descargar Plantilla Push</a>
                    </li>
                </ul>
                <form class="form-inline mt-2 mt-md-0">
                    <a href="cerrarsesion.php" class="btn btn-danger">Cerrar sesión</a>
                </form>
            </div>
        </div>
    </nav>
</header>

<!-- Begin page content -->

<div class="container">
    <div class="row align-items-start text-center">
        <div class="col">
            <h3 class="mt-3">Sistema de notificaciones AJETRON</h3>
        </div>
        <div class="col">
            <img src="ajetron.png" alt="Imagen de encabezado" class="img-fluid mt-3" style="max-width: 150px;">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12 col-md-12">
            <!-- Contenido -->
            <div class="outer-container">
                <form action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                    <div>
                        <div class="row">

                            <div class="input-group mb-3">
                                <label class="input-group-text" for="file">Elija archivo csv</label>
                                <input type="file" class="form-control" name="file" id="file" accept=".csv">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="delimiter">Seleccione el delimitador: </label>
                                <select class="form-select form-select-sm" aria-label="Default select example"
                                        name="delimiter" id="delimiter">
                                    <option value=",">Coma (,)</option>
                                    <option value=";">Punto y coma (;)</option>
                                </select>
                            </div>

                        </div>

                        <button type="submit" id="submit" name="enviar" class="btn btn-warning">Importar Registros
                        </button>
                        <button type="submit" id="submit" name="delete" class="btn btn-warning">Borrar Registros
                        </button>
                    </div>
                </form>
                <div class="col">
                    <h3 class="mt-3">Notas</h3>
                    <p class="font-weight-bold">La programacion tiene 3 horarios establecidos de Lunes a Sabado:</p>
                    <p class="font-weight-normal">tipo1: 8 am GMT-5</p>
                    <p class="font-weight-normal">tipo2: 11 am GMT-5</p>
                    <p class="font-weight-normal">tipo3: 3 pm GMT-5</p>
                </div>
            </div>

            <div class="outer-container" id="response" class="<?php if (!empty($type)) {
                echo $type . " display-block";
            } ?>"><?php if (!empty($message)) {
                    echo $message;
                } ?>
            </div>
            <br>


            <?php
            $sqlSelect = "SELECT * FROM push_carga";
            $result = mysqli_query($mysqli, $sqlSelect);

            if (mysqli_num_rows($result) > 0) {
                ?>

                <table class='table table-bordered'>
                    <thead>
                    <tr>
                        <th colspan="1">id</th>
                        <th colspan="3">pais</th>
                        <th colspan="2">descripcion</th>
                        <th colspan="1">tipo</th>
                        <th colspan="2">valor</th>
                        <th colspan="1">etiqueta</th>
                        <th colspan="1">programacion</th>
                        <th colspan="1">opcion</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                        <td colspan="1"><?php echo $row['id']; ?></td>
                        <td colspan="3"><?php echo $row['pais']; ?></td>
                        <td colspan="2"><?php echo $row['descripcion']; ?></td>
                        <td colspan="1"><?php echo $row['tipo']; ?></td>
                        <td colspan="2"><?php echo $row['valor']; ?></td>
                        <td colspan="1"><?php echo $row['etiqueta']; ?></td>
                        <td colspan="1"><?php echo $row['programacion']; ?></td>
                        <td colspan="2">
                            <form method="post" action="">
                                <button type="submit" id="submit" name="eliminar" value=<?php echo $row['id']; ?> type="button" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>

                            <form method="post" action="enviar_manual.php">
                                <button type="submit" id="submit" name="id_camp" value=<?php echo $row['id']; ?> type="button" class="btn btn-primary btn-sm">Enviar</button>
                            </form>
                        </td>
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
