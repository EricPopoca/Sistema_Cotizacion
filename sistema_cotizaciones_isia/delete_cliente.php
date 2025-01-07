<?php
session_start();
include('db.php');
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar cliente de la base de datos
    $query = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: clientes.php'); // Redirige a la página de clientes
    } else {
        echo "Error al eliminar el cliente.";
    }
} else {
    echo "ID no proporcionado.";
}
?>
