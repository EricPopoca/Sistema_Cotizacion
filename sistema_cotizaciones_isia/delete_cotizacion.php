<?php
session_start();
include('db.php');
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar cotización de la base de datos
    $query = "DELETE FROM cotizaciones WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: cotizaciones.php'); // Redirige a la página de cotizaciones
    } else {
        echo "Error al eliminar la cotización.";
    }
} else {
    echo "ID no proporcionado.";
}
?>
