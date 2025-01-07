<?php
session_start();
include('db.php');

// Verificar si el formulario ha sido enviado
if (isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Verifica si la conexión a la base de datos está activa
    if (!$conn) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para obtener el usuario por correo
    $query = "SELECT * FROM administradores WHERE correo = ?";
    $stmt = $conn->prepare($query);

    // Verifica si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Vincula el parámetro para la consulta
    $stmt->bind_param("s", $correo);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        // Verifica si se encontró el usuario
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Verifica si la contraseña es correcta
            if (password_verify($contrasena, $user['contrasena'])) {
                $_SESSION['admin'] = $user['id'];
                header('Location: index.php'); // Redirige al panel principal
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    } else {
        $error = "Error al ejecutar la consulta: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <br>
    <title>SISTEMA DE COTIZACIONES ISIA - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .login-box {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-box h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            color: #007bff;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center text-muted">SISTEMA DE COTIZACIONES ISIA</h1>
        <div class="login-box">
            <h1>Iniciar Sesión</h1>
            <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="text" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Ingresar</button>
            </form>
            <p class="register-link">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>
