<?php

session_start();
global $mysqli;
require_once 'dbconect.php';

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
clearstatcache();

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
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Agrega el enlace para DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/colvis/1.2.0/css/dataTables.colVis.min.css">

<!-- Agrega la librería jQuery -->


</head>

<body>
<header>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark"> <a class="navbar-brand" href="#">AJEPER</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active"> <a class="nav-link" href="carga.php">PUSH <span class="sr-only">(current)</span></a> </li>
      </ul>
      <form class="form-inline mt-2 mt-md-0">
      <a href="cerrarsesion.php" class="btn btn-danger">Cerrar sesión</a>
      </form>
    </div>
  </nav>
</header>

<!-- Begin page content -->

<div class="container">
    <br>
    <div class="row">
  <div class="col-md-6 text-center">
    <h3 class="mt-5">Sistema de notificaciones AJETRON</h3>
  </div>
  <div class="col-md-6 text-center">
    <br>
    <img src="ajetron.png" alt="Imagen de encabezado" class="img-fluid" style="max-width: 150px;">
  </div>
</div>
  <hr>
  <div class="row">
    <div class="col-12 col-md-12">
      <!-- Contenido -->

<?php
global $mysqli;
require_once 'dbconect.php';
    $sqlSelect = "SELECT * FROM v_push_base";
    $result = mysqli_query($mysqli, $sqlSelect);

if (mysqli_num_rows($result) > 0)
{
?>

    <table id="dataTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>idtelegram</th>
                <th>ruta</th>
                <th>region</th>
                <th>subregion</th>
                <th>zona</th>
                <th>nombre_vendedor</th>
            </tr>
        </thead>
        <tbody>
<?php
    while ($row = mysqli_fetch_array($result)) {
?>
        
        <tr>
            <td><?php  echo $row['idtelegram']; ?></td>
            <td><?php  echo $row['ruta']; ?></td>
            <td><?php  echo $row['region']; ?></td>
            <td><?php  echo $row['subregion']; ?></td>
            <td><?php  echo $row['zona']; ?></td>
            <td><?php  echo $row['nombre_vendedor']; ?></td>
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
    </span> </div>
</footer>
<!-- Agrega el script para DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/colvis/1.2.0/js/dataTables.colVis.min.js"></script>

<script>
    // Inicializa DataTables en la tabla con extensiones
    $(document).ready(function() {
        $('#dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            headerFontSize: '2px', // Tamaño de fuente para los encabezados de columna
            cellFontSize: '2px',
            colVis: {
                buttonText: 'Mostrar/Ocultar columnas'
            },
            lengthMenu: [10, 50, 100], // Opciones de cantidad de registros por página
            pageLength: 10
        });
    });
</script>


<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

</body>
</html>
