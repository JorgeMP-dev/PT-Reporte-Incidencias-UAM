<?php
	require ('lib/var/varlib.php');
  require ($_RUTADB);
	session_start();
	if(isset($_SESSION["usuario"])){
		header("Location: index.php");
        exit;
	}
    $message='';
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $usuario = trim($_POST['usuario']);
        $password = $_POST['contraseña'];

        if ($usuario !== "" && $password !== "") {

            $sql = "SELECT 
                        u.idusuario,
                        u.usuario,
                        p.nombre,
                        p.idpersonal,
                        p.apellidoP,
                        u.contraseña
                    FROM usuario u
                    INNER JOIN personal p ON u.idpersonal = p.idpersonal
                    WHERE u.usuario = ? AND u.estado='activo'";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {

                if (password_verify($password, $row['contraseña'])) {

                    session_regenerate_id(true);

                    $_SESSION['usuario'] = $row['usuario'];
                    $_SESSION['nombre'] = $row['nombre'];
                    $_SESSION['apellido'] = $row['apellidoP'];
                    $_SESSION['id_usuario'] = $row['idusuario'];
                    $_SESSION['id_personal'] = $row['idpersonal'];

                    $sqlPermisos = "SELECT 
                                    m.nombre AS modulo,
                                    tp.nombre AS tipoPermiso
                                    FROM permiso p
                                    JOIN modulo m ON p.idmodulo = m.idmodulo
                                    JOIN tipoPermiso tp ON p.idtipoPermiso = tp.idtipoPermiso
                                    WHERE p.idusuario = ?";

                    $stmtPerm = $conn->prepare($sqlPermisos);
                    $stmtPerm->bind_param("i", $row['idusuario']);
                    $stmtPerm->execute();
                    $resultPerm = $stmtPerm->get_result();

                    $permisos = [];

                    while($permiso = $resultPerm->fetch_assoc()){
                        $permisos[$permiso['modulo']] = $permiso['tipoPermiso'];
                    }

                    $_SESSION['permisos'] = $permisos;

                    header("Location: animacionLogin.php");
                    exit;
                }
            }

            $message = "Usuario o contraseña incorrectos";
        }
    }
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="lib/css/bootstrap.min.css" rel="stylesheet">
<title>Login</title>
</head>
<body class="bg-dark text-light d-flex align-items-center vh-100">

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <div class="card shadow-lg">
        <div class="card-body text-center">

          <img src="img/sistemasLogo.webp" width="180" class="mb-3">

          <h5 class="mb-3">Acceso de Empleados</h5>

          <?php if($message): ?>
            <div class="alert alert-danger"><?= $message ?></div>
          <?php endif; ?>

          <form method="POST">

            <input class="form-control mb-3" name="usuario" placeholder="Usuario" required>

            <input class="form-control mb-3" type="password" name="contraseña" placeholder="Contraseña" required>

            <button class="btn btn-danger w-100">Ingresar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="lib/js/bootstrap.bundle.min.js"></script>
</body>
</html>