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
$active = "familias";
// conexión

if (isset($_POST["delete"])) {
    $query = "DELETE FROM comisiones_familias WHERE pais = '".$paisSession."'";
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

            if ($paisSession == 'GT') {
                $q = "INSERT INTO comisiones_familias (pais,cod_familia,s1,s2,s3,s4,s5,s6,s7,s8,s9,s10,se1,se2,se3,se4,se5,se6,se7,se8,se9,se10) VALUES (
                    '$data[0]',
                    '$data[1]',
                    '$data[2]',
                    '$data[3]',
                    '$data[4]',
                    '$data[5]',
                    '$data[6]',
                    '$data[7]',
                    '$data[8]',
                    '$data[9]',
                    '$data[10]',
                    '$data[11]',
                    '$data[12]',
                    '$data[13]',
                    '$data[14]',
                    '$data[15]',
                    '$data[16]',
                    '$data[17]',
                    '$data[18]',
                    '$data[19]',
                    '$data[20]',
                    '$data[21]'        
                    )";
            } else {
                if ($paisSession == 'HN' || $paisSession == 'SV'){
                    $q = "INSERT INTO comisiones_familias (pais,articulo,cod_familia,s1,s2,s3,s4,s5,s6,s7,s8,s9,s10,se1,se2,se3,se4,se5,se6,se7,se8,se9,se10) VALUES (
                                        '$data[0]',
                                        '$data[1]',
                                        '$data[2]',
                                        '$data[3]',
                                        '$data[4]',
                                        '$data[5]',
                                        '$data[6]',
                                        '$data[7]',
                                        '$data[8]',
                                        '$data[9]',
                                        '$data[10]',
                                        '$data[11]',
                                        '$data[12]',
                                        '$data[13]',
                                        '$data[14]',
                                        '$data[15]',
                                        '$data[16]',
                                        '$data[17]',
                                        '$data[18]',
                                        '$data[19]',
                                        '$data[20]',
                                        '$data[21]'
                                        )";
                }else{
                    $q = "INSERT INTO comisiones_familias (pais,articulo,s1,s2,s3,s4,s5,s6,s7,s8,s9,s10,se1,se2,se3,se4,se5,se6,se7,se8,se9,se10) VALUES (
                                        '$data[0]',
                                        '$data[1]',
                                        '$data[2]',
                                        '$data[3]',
                                        '$data[4]',
                                        '$data[5]',
                                        '$data[6]',
                                        '$data[7]',
                                        '$data[8]',
                                        '$data[9]',
                                        '$data[10]',
                                        '$data[11]',
                                        '$data[12]',
                                        '$data[13]',
                                        '$data[14]',
                                        '$data[15]',
                                        '$data[16]',
                                        '$data[17]',
                                        '$data[18]',
                                        '$data[19]',
                                        '$data[20]',
                                        '$data[21]'
                                        )";
                }
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
            <h3 class="mt-3">Carga Plantilla Familias</h3>
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
                                    <li><a class="dropdown-item" href="static/gt_cobertura_cliente_objetivo.csv">GT - Cobertura</a></li>
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
            $sqlSelect = "SELECT * FROM comisiones_familias where pais = '".$paisSession."'";
            $result = mysqli_query($mysqli, $sqlSelect);

            if (mysqli_num_rows($result) > 0) {
                ?>

                <table class='table table-bordered'>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>pais</th>
                        <th>articulo</th>
                        <?php
                        if ($paisSession == 'HN' || $paisSession == 'SV') {
                           echo "<th>cod_familia</th>";
                        }
                        ?>
                        <th>s1</th>
                        <th>s2</th>
                        <th>s3</th>
                        <th>s4</th>
                        <th>s5</th>
                        <th>s6</th>
                        <th>s7</th>
                        <th>s8</th>
                        <th>s9</th>
                        <th>s10</th>
                        <th>se1</th>
                        <th>se2</th>
                        <th>se3</th>
                        <th>se4</th>
                        <th>se5</th>
                        <th>se6</th>
                        <th>se7</th>
                        <th>se8</th>
                        <th>se9</th>
                        <th>se10</th>
                    </tr>
                    </thead>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tbody>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['pais']; ?></td>
                        <?php
                        // Solo mostrar las columnas "Formato" y "Tipo Formato" si el país es "GT"
                        if ($paisSession == 'GT') {
                            echo "<td>" . $row['cod_familia'] . "</td>";
                        } else {
                            if ($paisSession == 'HN' || $paisSession == 'SV') {
                               echo "<td>" . $row['articulo'] . "</td>"."<td>" . $row['cod_familia'] . "</td>";
                            }else{
                               echo "<td>" . $row['articulo'] . "</td>";
                            }
                        }
                        ?>
                        <td><?php echo $row['s1']; ?></td>
                        <td><?php echo $row['s2']; ?></td>
                        <td><?php echo $row['s3']; ?></td>
                        <td><?php echo $row['s4']; ?></td>
                        <td><?php echo $row['s5']; ?></td>
                        <td><?php echo $row['s6']; ?></td>
                        <td><?php echo $row['s7']; ?></td>
                        <td><?php echo $row['s8']; ?></td>
                        <td><?php echo $row['s9']; ?></td>
                        <td><?php echo $row['s10']; ?></td>
                        <td><?php echo $row['se1']; ?></td>
                        <td><?php echo $row['se2']; ?></td>
                        <td><?php echo $row['se3']; ?></td>
                        <td><?php echo $row['se4']; ?></td>
                        <td><?php echo $row['se5']; ?></td>
                        <td><?php echo $row['se6']; ?></td>
                        <td><?php echo $row['se7']; ?></td>
                        <td><?php echo $row['se8']; ?></td>
                        <td><?php echo $row['se9']; ?></td>
                        <td><?php echo $row['se10']; ?></td>
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
