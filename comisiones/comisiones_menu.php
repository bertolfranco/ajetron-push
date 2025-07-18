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
                    <a <?php if ($active == "celula") {
                        echo 'class="nav-link active"';
                    } else {
                        echo 'class="nav-link"';
                    } ?> href="comisiones_celula.php">
                        Comisiones Celula
                    </a>
                </li>
                <li class="nav-item active">
                    <a <?php if ($active == "banda") {
                        echo 'class="nav-link active"';
                    } else {
                        echo 'class="nav-link"';
                    } ?> href="comisiones_banda.php">
                        Comisiones Banda
                    </a>
                </li>
                <?php if ($paisSession == 'PE'): ?>
                    <li class="nav-item active">
                        <a <?php if ($active == "hitrate") {
                            echo 'class="nav-link active"';
                        } else {
                            echo 'class="nav-link"';
                        } ?> href="comisiones_hitrate.php">
                            Comisiones Hit Rate
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item active">
                    <a <?php if ($active == "familias") {
                        echo 'class="nav-link active"';
                    } else {
                        echo 'class="nav-link"';
                    } ?> href="comisiones_familias.php">
                        Comisiones Familias
                    </a>
                </li>
                <li class="nav-item active">
                    <a <?php if ($active == "tipo") {
                        echo 'class="nav-link active"';
                    } else {
                        echo 'class="nav-link"';
                    } ?>
                        href="comisiones_tipo_comision.php">
                        Comisiones Tipo Comision
                    </a>
                </li>
                <li class="nav-item active">
                    <a <?php if ($active == "foco") {
                        echo 'class="nav-link active"';
                    } else {
                        echo 'class="nav-link"';
                    } ?>
                        href="comisiones_foco.php">
                        Comisiones Foco
                    </a>
                </li>
                <li class="nav-item active">
                    <a <?php if ($active == "gtcobertura") {
                        echo 'class="nav-link active"';
                    } else {
                        echo 'class="nav-link"';
                    } ?> href="comisiones_gt_cobertura.php">
                        GT Cobertura
                    </a>
                </li>
                <?php if ($paisSession == 'GT'): ?>
                    <li class="nav-item active">
                        <a <?php if ($active == "gtadicional") {
                            echo 'class="nav-link active"';
                        } else {
                            echo 'class="nav-link"';
                        } ?> href="comisiones_gt_adicionales.php">
                            GT Adicionales
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (in_array($paisSession, ['GT', 'HN', 'SV'])): ?>
                    <li class="nav-item active">
                        <a <?php if ($active == "kpis") {
                            echo 'class="nav-link active"';
                        } else {
                            echo 'class="nav-link"';
                        } ?>
                            href="comisiones_kpis.php">
                            Kpis
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (in_array($paisSession, ['GT', 'HN', 'SV'])): ?>
                    <li class="nav-item active">
                        <a <?php if ($active == "tpc") {
                            echo 'class="nav-link active"';
                        } else {
                            echo 'class="nav-link"';
                        } ?>
                            href="comisiones_parametros.php">
                            Param. Conf.
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item active">
                    <a <?php if ($active == "dias") {
                        echo 'class="nav-link active"';
                    } else {
                        echo 'class="nav-link"';
                    } ?>
                        href="comisiones_diashabiles.php">
                        Comisiones Dias Habiles
                    </a>
                </li>
                <?php if ($paisSession == 'PE'): ?>
                    <li class="nav-item active">
                        <a <?php if ($active == "concurso") {
                            echo 'class="nav-link active"';
                        } else {
                            echo 'class="nav-link"';
                        } ?> href="comisiones_concursos.php">
                            Comisiones Concursos
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($paisSession == 'PE'): ?>
                    <li class="nav-item active">
                        <a <?php if ($active == "clientesprograma") {
                            echo 'class="nav-link active"';
                        } else {
                            echo 'class="nav-link"';
                        } ?> href="clientes_programas.php">
                            Clientes Programa
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item active">
                    <a <?php if ($active == "historico") {
                        echo 'class="nav-link active"';
                    } else {
                        echo 'class="nav-link"';
                    } ?> href="comisiones_comisiones_historica.php">
                        Históricos
                    </a>
                </li>
            </ul>
            <form class="form-inline mt-2 mt-md-0">
                <a href="../cerrarsesion.php" class="btn btn-danger">Cerrar sesión</a>
            </form>
        </div>
    </div>
</nav>