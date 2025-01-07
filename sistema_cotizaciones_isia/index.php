<?php 
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Sistema de Cotizaciones ISIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 900px;
            margin-top: 100px;
        }
        .esia-logo {
            width: 150px;
            height: auto;
            margin-bottom: 30px;
        }
        h1 {
            text-align: center;
            color: #004085;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .nav {
            background-color: #007bff;
            border-radius: 8px;
            padding: 10px 0;
            margin-top: 30px;
        }
        .nav-item {
            margin: 0 15px;
        }
        .nav-link {
            color: white !important;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .nav-link:hover {
            background-color: #0056b3;
            border-radius: 5px;
        }
        .nav-item:last-child {
            margin-right: 0;
        }
        .logout-btn {
            background-color: #e63946;
            color: white;
            font-weight: bold;
            border-radius: 5px;
        }
        .logout-btn:hover {
            background-color: #d63031;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1>Bienvenido al Sistema de Cotizaciones de ISIA</h1>
        <img src="images/isia-logo.jpg" alt="ISIA Logo" class="isia-logo">
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="cotizaciones.php">Cotizaciones</a></li>
                <li class="nav-item"><a class="nav-link" href="ajustes.php">Ajustes</a></li>
                <li class="nav-item"><a class="nav-link logout-btn" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
