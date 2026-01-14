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
                        <a <?php if($active == "compensacion" ) {echo 'class="nav-link active"'; } else {echo 'class="nav-link"'; } ?> href="comisiones_modelo_compensacion.php">
                            Modelo Compensacion
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a <?php if($active == "banda" ) {echo 'class="nav-link active"'; } else {echo 'class="nav-link"'; } ?> href="comisiones_banda.php">
                            Comisiones Banda
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a <?php if($active == "tipo" ) {echo 'class="nav-link active"'; } else {echo 'class="nav-link"'; } ?> href="comisiones_tipo_comision.php">
                            Comisiones Tipo Comision
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a <?php if($active == "cobertura" ) {echo 'class="nav-link active"'; } else {echo 'class="nav-link"'; } ?> href="comisiones_objetivo_cobertura.php">
                            Objetivo Cobertura
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a <?php if($active == "fechas" ) {echo 'class="nav-link active"'; } else {echo 'class="nav-link"'; } ?> href="comisiones_fechashabiles.php">
                            Fechas Habiles
                        </a>
                    </li>
					<li class="nav-item active">
                        <a <?php if($active == "ipp" ) {echo 'class="nav-link active"'; } else {echo 'class="nav-link"'; } ?> href="comisiones_ipp_actual.php">
                            Ipp Actual
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a <?php if($active == "historico" ) {echo 'class="nav-link active"'; } else {echo 'class="nav-link"'; } ?> href="comisiones_comisiones_historica.php">
                            Históricos
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a <?php if ($active == "usuarios") {
                            echo 'class="nav-link active"';
                        } else {
                            echo 'class="nav-link"';
                        } ?> href="ajetron_usuarios.php">
                            Usuarios
                        </a>
                    </li>
                </ul>
                <form class="form-inline mt-2 mt-md-0">
                    <a href="../cerrarsesion.php" class="btn btn-danger">Cerrar sesión</a>
                </form>
            </div>
        </div>
    </nav>