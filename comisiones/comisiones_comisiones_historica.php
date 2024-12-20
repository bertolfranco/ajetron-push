<?php
require_once '/assets/jpgraph-4.2.10/src/jpgraph.php';
require_once '/assets/jpgraph-4.2.10/src/jpgraph_canvas.php';
require_once '/assets/jpgraph-4.2.10/src/jpgraph_table.php';

session_start();
global $mysqli;
require_once 'dbconect.php';

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
clearstatcache();

$paisSession = $_SESSION["pais"];
$active = "compensacion";
// conexión

if (isset($_POST["delete"])) {
    $query = "DELETE FROM modelos_compensacion WHERE pais = '".$paisSession."'";
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

            $q = "INSERT INTO modelos_compensacion (pais,ruta,descripcion,modelo) VALUES (
            '$data[0]',
            '$data[1]',
            '$data[2]',
            '$data[3]'
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
            <h3 class="mt-3">Historico de Comisiones</h3>
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
                        <div class="row">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="file">Escriba ruta</label>
                                <input type="text" class="form-control" name="file" id="file">
                            </div>

                            <div class="input-append date" id="datepicker" data-date="02-2012"
                                 data-date-format="mm-yyyy">
                             <input  type="text" readonly="readonly" name="date" >
                             <span class="add-on"><i class="icon-th"></i></span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="delimiter">Seleccione pais: </label>
                                <select class="form-select form-select-sm" aria-label="Default select example"
                                        name="delimiter" id="delimiter">
                                    <option value="CO">Colombia</option>
                                    <option value="PE">Perú</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="CO">Colombia</option>
                                    <option value="PA">Panamá</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="MX">Mexico</option>
                                    <option value="BO">Bolivia</option>
                                </select>
                            </div>

                        </div>

                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" id="submit" name="enviar" class="btn btn-warning">Generar Imagen
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
            $sqlSelect = "SELECT * FROM modelos_compensacion where pais = '".$paisSession."'";
            $result = mysqli_query($mysqli, $sqlSelect);

            if (mysqli_num_rows($result) > 0) {
                ?>

                <table class='table table-bordered'>
                    <thead>
                    <tr>
                        <th>Pais</th>
                        <th>Ruta</th>
                        <th>Descripcion</th>
                        <th>Modelo</th>
                    </tr>
                    </thead>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tbody>
                    <tr>
                        <td><?php echo $row['pais']; ?></td>
                        <td><?php echo $row['ruta']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td><?php echo $row['modelo']; ?></td>
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
