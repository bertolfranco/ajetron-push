<?php

session_start();
global $mysqli;
require_once 'dbconect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $mysqli->prepare("SELECT username, pais FROM push_usuarios WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
		$followingdata = $result->fetch_assoc();
		$pais = $followingdata["pais"];
        $_SESSION["username"] = $username;
		$_SESSION["pais"] = $pais;
        header("Location: carga.php");
        exit();
    } else {
        $login_error = "Credenciales incorrectas";
    }

    $stmt->close();
    $mysqli->close();
}

?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Iniciar Sesión - Sistema de Notificaciones</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center">
                    <h2>Sistema de Notificaciones</h2>
                    <img src="ajetron.png" alt="Logo" class="img-fluid mt-3" style="max-width: 150px;">
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="mb-0">Iniciar Sesión</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="username">Usuario:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                            <?php if(isset($login_error)) { echo "<p class='text-danger text-center mt-3'>$login_error</p>"; } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
