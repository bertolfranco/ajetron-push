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
// conexión

if (isset($_POST["delete"])) {
    $query = "DELETE FROM comisiones_banda WHERE pais = '".$paisSession."'";
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
			
			if($paisSession == 'GT'){
				$q = "INSERT INTO comisiones_banda (pais,a1,p1,a2,p2,a3,p3,a4,p4,a5,p5,a6,p6,a7,p7,a8,p8,a9,p9,a10,p10,a11,p11) VALUES (
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
				'$data[21]',
				'$data[22]'
            )";
			}else {
				$q = "INSERT INTO comisiones_banda (pais,a1,p1,a2,p2,a3,p3,a4,p4,a5,p5,a6,p6,a7,p7,a8,p8,a9,p9,a10,p10) VALUES (
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
				'$data[20]'
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
                        <a class="nav-link" href="../carga.php">
                            PUSH
                            <span class="sr-only">(Volver)</span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="comisiones_celula.php">
                            Comisiones Celula
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="comisiones_banda.php">
                            Comisiones Banda
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="comisiones_hitrate.php">
                            Comisiones Hit Rate
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="comisiones_familias.php">
                            Comisiones Familias
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="comisiones_tipo_comision.php">
                            Comisiones Tipo Comision
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="comisiones_foco.php">
                            Comisiones Foco
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="comisiones_gt_cobertura.php">
                            GT Cobertura
                        </a>
                    </li>
                </ul>
                <form class="form-inline mt-2 mt-md-0">
                    <a href="../cerrarsesion.php" class="btn btn-danger">Cerrar sesión</a>
                </form>
            </div>
        </div>
    </nav>
</header>

<!-- Begin page content -->

<div class="container">
    <div class="row align-items-start text-center">
        <div class="col">
            <h3 class="mt-3">Carga Plantilla Banda</h3>
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
            $sqlSelect = "SELECT * FROM comisiones_banda where pais = '".$paisSession."'";
            $result = mysqli_query($mysqli, $sqlSelect);

            if (mysqli_num_rows($result) > 0) {
                ?>

                <table class='table table-bordered'>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Pais</th>
                        <th>a1</th>
                        <th>p1</th>
                        <th>a2</th>
                        <th>p2</th>
                        <th>a3</th>
                        <th>p3</th>
                        <th>a4</th>
                        <th>p4</th>
                        <th>a5</th>
                        <th>p5</th>
                        <th>a6</th>
                        <th>p6</th>
                        <th>a7</th>
                        <th>p7</th>
                        <th>a8</th>
                        <th>p8</th>
                        <th>a9</th>
                        <th>p9</th>
                        <th>a10</th>
                        <th>p10</th>
						<th>a11</th>
                        <th>p11</th>
                    </tr>
                    </thead>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tbody>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['pais']; ?></td>
                        <td><?php echo $row['a1']; ?></td>
                        <td><?php echo $row['p1']; ?></td>
                        <td><?php echo $row['a2']; ?></td>
                        <td><?php echo $row['p2']; ?></td>
                        <td><?php echo $row['a3']; ?></td>
                        <td><?php echo $row['p3']; ?></td>
                        <td><?php echo $row['a4']; ?></td>
                        <td><?php echo $row['p4']; ?></td>
                        <td><?php echo $row['a5']; ?></td>
                        <td><?php echo $row['p5']; ?></td>
                        <td><?php echo $row['a6']; ?></td>
                        <td><?php echo $row['p6']; ?></td>
                        <td><?php echo $row['a7']; ?></td>
                        <td><?php echo $row['p7']; ?></td>
                        <td><?php echo $row['a8']; ?></td>
                        <td><?php echo $row['p8']; ?></td>
                        <td><?php echo $row['a9']; ?></td>
                        <td><?php echo $row['p9']; ?></td>
                        <td><?php echo $row['a10']; ?></td>
                        <td><?php echo $row['p10']; ?></td>
						<td><?php echo $row['a11']; ?></td>
                        <td><?php echo $row['p11']; ?></td>
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
