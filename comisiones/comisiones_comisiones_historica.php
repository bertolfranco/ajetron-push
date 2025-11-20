<?php
require_once '../assets/jpgraph-4.2.10/src/jpgraph.php';
require_once '../assets/jpgraph-4.2.10/src/jpgraph_canvas.php';
require_once '../assets/jpgraph-4.2.10/src/jpgraph_table.php';

session_start();
global $mysqli;
require_once 'dbconect.php';

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
clearstatcache();

$paisSession = $_SESSION["pais"];
$active = "historico";

// conexión

if (isset($_POST["delete"])) {
    $query = "DELETE FROM modelos_compensacion WHERE pais = '".$paisSession."'";
    $resultados = mysqli_query($mysqli, $query);

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
                <form action="" name="frmExcelImport" id="formDescargar" method="post" enctype="multipart/form-data">
                    <div>
                        <div class="row">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="ruta">Escriba ruta</label>
                                <input type="text" class="form-control" name="ruta" id="ruta">
                            </div>

                            <div class="col-sm">
                                <label class="form-label" for="mes">Seleccione Mes: </label>
                                <select class="form-select form-select-sm" aria-label="Default select example"
                                        name="mes" id="mes">
                                    <option value="01">Enero</option>
                                    <option value="02">Febrero</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Mayo</option>
                                    <option value="06">Junio</option>
                                    <option value="07">Julio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Setiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label class="form-label" for="anio">Seleccione Año: </label>
                                <select class="form-select form-select-sm" aria-label="Default select example"
                                        name="anio" id="anio">
                                    <option value="2025">2025</option>
                                </select>
                            </div>

                            <div class="col-sm">
                                <label class="form-label" for="pais">Seleccione pais: </label>
                                <select class="form-select form-select-sm" aria-label="Default select example"
                                        name="pais" id="pais">
                                    <?php
                                    if ($paisSession == "AD"){
                                        echo '<option value="CO">Colombia</option>
                                        <option value="PE">Perú</option>
                                        <option value="EC">Ecuador</option>
                                        <option value="CO">Colombia</option>
                                        <option value="PA">Panamá</option>
                                        <option value="GT">Guatemala</option>
                                        <option value="HN">Honduras</option>
                                        <option value="SV">El Salvador</option>
                                        <option value="CR">Costa Rica</option>
                                        <option value="MX">Mexico</option>
                                        <option value="BO">Bolivia</option>';
                                    }
                                    else{
                                        echo '<option value="'.$paisSession.'">Colombia</option>"';
                                    } ?>
                                </select>
                            </div>

                        </div>

                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="submit" id="submit" name="generar" class="btn btn-warning">Generar Imagen
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
<script>
document.getElementById('formDescargar').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('descargar_imagen.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.headers.get('Content-Type') === 'application/json') {
            return response.json().then(data => {
                alert(data.mensaje);
            });
        } else {
            const contentDisposition = response.headers.get('Content-Disposition') || '';
            let filename = 'archivo_descargado.png';

            // Buscar filename en el header
            const match = contentDisposition.match(/filename="?(.+?)"?$/);
            if (match && match[1]) {
                filename = match[1];
            }

            return response.blob().then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
            });
        }
    })
    .catch(error => {
        alert('Error al procesar la solicitud.');
        console.error(error);
    });
});
</script>
</body>
</html>
