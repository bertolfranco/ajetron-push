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
$active = "gtcobertura";
// conexión

if (isset($_POST["delete"])) {
    $query = "DELETE FROM cobertura_clientes_objetivo WHERE pais = '".$paisSession."'";
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

            if ($pais == 'GT') {
                // Inserción cuando el país es 'GT', incluyendo 'formato' y 'tipo_formato'
                $q = "INSERT INTO cobertura_clientes_objetivo (pais, cod_zona, cod_ruta, desc_marca,formato, tipo_formato, objetivo_clientes, valor, sistema) VALUES (
                        '$data[0]',
                        '$data[1]',
                        '$data[2]',
                        '$data[3]',
                        '$data[4]',
                        '$data[5]',
                        '$data[6]',
                        '$data[7]',
                        '$data[8]'
                       )";
            } else {
                // Inserción cuando el país no es 'GT', sin 'formato' y 'tipo_formato'
                $q = "INSERT INTO cobertura_clientes_objetivo (pais, cod_zona, cod_ruta, desc_marca, objetivo_clientes, valor, sistema) VALUES (
                        '$data[0]',
                        '$data[1]',
                        '$data[2]',
                        '$data[3]',
                        '$data[4]',
                        '$data[5]',
                        '$data[6]'
                       )";
            }

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
            <h3 class="mt-3">Carga Plantilla GT Cobertura</h3>
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

                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="submit" id="submit" name="enviar" class="btn btn-warning">Importar Registros
                            </button>
                            <button type="submit" id="submit" name="delete" class="btn btn-warning">Borrar Registros
                            </button>

                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    Descargar Plantillas
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="static/1_plantilla_celula.csv">Celula</a></li>
                                    <li><a class="dropdown-item" href="static/2_plantilla_banda.csv">Banda</a></li>
                                    <li><a class="dropdown-item" href="static/8_plantilla_hitrate.csv">Hit Rate</a></li>
                                    <li><a class="dropdown-item" href="static/3_plantilla_familias.csv">Familias</a></li>
                                    <li><a class="dropdown-item" href="static/4_plantilla_tipo_comision.csv">Tipo Comision</a></li>
                                    <li><a class="dropdown-item" href="static/5_plantilla_foco.csv">Foco</a></li>
                                    <li><a class="dropdown-item" href="static/6_plantilla_cobertura.csv">GT - Cobertura</a></li>
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
            $sqlSelect = "SELECT * FROM cobertura_clientes_objetivo where pais = '".$paisSession."' ORDER BY cod_ruta,desc_marca";
            $result = mysqli_query($mysqli, $sqlSelect);

            if (mysqli_num_rows($result) > 0) {
                ?>

                <table class='table table-bordered'>
                    <thead>
                    <tr>
                        <th>Pais</th>
                        <th>Cod_zona</th>
                        <th>Cod_ruta</th>
                        <?php
                        if ($paisSession == 'GT') {
                            echo "<th>Tipo</th>";
                        } else {
                            echo "<th>Desc_marca</th>";
                        }
                         ?>
                        <?php
                        // Solo mostrar "Formato" y "Tipo Formato" si el país es "GT"
                        if ($paisSession == 'GT') {
                            echo "<th>Formato</th>";
                            echo "<th>Tipo Formato</th>";
                        }
                        ?>
                        <th>Objetivo_clientes</th>
						<th>Valor</th>
						<th>Sistema</th>
                    </tr>
                    </thead>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tbody>
                    <tr>
                        <td><?php echo $row['pais']; ?></td>
                        <td><?php echo $row['cod_zona']; ?></td>
                        <td><?php echo $row['cod_ruta']; ?></td>
                        <td><?php echo $row['desc_marca']; ?></td>
                        <?php
                        // Solo mostrar las columnas "Formato" y "Tipo Formato" si el país es "GT"
                        if ($paisSession == 'GT') {
                            echo "<td>" . $row['formato'] . "</td>";
                            echo "<td>" . $row['tipo_formato'] . "</td>";
                        }
                        ?>
                        <td><?php echo $row['objetivo_clientes']; ?></td>
						<td><?php echo $row['valor']; ?></td>
						<td><?php echo $row['sistema']; ?></td>
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
