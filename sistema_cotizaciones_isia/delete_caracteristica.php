<?php
session_start();
include('db.php');

// Verifica si el administrador está logueado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Verificar si se pasa el id de la característica a eliminar
if (isset($_GET['caracteristica_id'])) {
    $caracteristica_id = $_GET['caracteristica_id'];

    // Eliminar la característica
    $query = "DELETE FROM caracteristicas WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $caracteristica_id);
    $stmt->execute();

    header('Location: detalles_cotizacion.php?id=' . $_GET['cotizacion_id']);
    exit;
} else {
    header('Location: cotizaciones.php');
    exit;
}
