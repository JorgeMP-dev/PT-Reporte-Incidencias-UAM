<?php
require('lib/var/varlib.php'); 
require ($_RUTADB);
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idpersonal = intval($_POST['idpersonal']);
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

    if ($usuario !== "" && $password !== "" && $idpersonal > 0) {

        // Hash seguro
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (idpersonal, usuario, contraseña)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $idpersonal, $usuario, $hash);

        if ($stmt->execute()) {
            $message = "Usuario creado correctamente";
        } else {
            $message = "Error al crear usuario (¿usuario duplicado?)";
        }
    } else {
        $message = "Completa todos los campos";
    }
}
?>

<form method="POST">

  <input class="form-control mb-2" name="idpersonal" placeholder="ID personal">

  <input class="form-control mb-2" name="usuario" placeholder="Usuario">

  <input class="form-control mb-2" type="password" name="password" placeholder="Contraseña">


  <button class="btn btn-success">Crear usuario</button>

</form>

<p><?= $message ?></p>