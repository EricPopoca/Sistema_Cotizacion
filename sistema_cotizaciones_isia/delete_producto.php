<?php
session_start();
include('db.php');

// Verifica si el administrador está logueado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Verifica si se ha pasado un id de producto a eliminar
if (isset($_GET['producto_id'])) {
    $producto_id = $_GET['producto_id'];

    // Elimina el producto de la base de datos
    $query = "DELETE FROM productos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $producto_id); // Asegúrate de pasar el id correcto del producto
    $stmt->execute();

    // Redirige a la página de detalles de cotización después de eliminar
    header('Location: detalles_cotizacion.php?id=' . $_GET['cotizacion_id']);
    exit;
} else {
    // Si no se pasó un id de producto, redirige a la página de cotizaciones
    header('Location: cotizaciones.php');
    exit;
}
?>
