<?php
session_start();
include('db.php');

// Verifica si el administrador está logueado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Verifica que se haya recibido el ID del producto desde la URL
if (isset($_GET['producto_id'])) {
    $producto_id = $_GET['producto_id']; // Obtén el ID del producto

    // Obtener los datos del producto para el formulario de edición
    $query = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $producto_id);  // Asegúrate de que el tipo de parámetro sea correcto (entero)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();  // Obtén el producto que se va a editar
    } else {
        // Si no se encuentra el producto, redirige
        header('Location: detalles_cotizacion.php?id=' . $_GET['cotizacion_id']);
        exit;
    }

    // Actualizar el producto cuando se envíe el formulario
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_producto'])) {
        // Obtener los datos del formulario
        $clave = $_POST['clave'];
        $modelo = $_POST['modelo'];
        $descripcion = $_POST['descripcion'];
        $precio_unitario = $_POST['precio_unitario'];
        $cantidad = $_POST['cantidad'];
        $importe = $precio_unitario * $cantidad; // Calcula el importe
    
        // Consulta para actualizar el producto
        $query_update = "UPDATE productos 
                         SET clave = ?, modelo = ?, descripcion = ?, precio_unitario = ?, cantidad = ?, importe = ?
                         WHERE id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("ssssdid", $clave, $modelo, $descripcion, $precio_unitario, $cantidad, $importe, $producto_id); // No olvides pasar el ID
        $stmt_update->execute(); // Ejecuta la actualización
    
        // Redirige para mostrar el resultado
        header('Location: detalles_cotizacion.php?id=' . $_GET['cotizacion_id']);
        exit;
    }
} else {
    // Si no se pasa el ID del producto, redirige a la lista de cotizaciones
    header('Location: cotizaciones.php');
    exit;
}
?>
<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Producto</h1>
        <form method="POST" action="">
    <div class="mb-3">
        <label for="clave" class="form-label">Clave</label>
        <input type="text" class="form-control" id="clave" name="clave" value="<?php echo $producto['clave']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="modelo" class="form-label">Modelo</label>
        <input type="text" class="form-control" id="modelo" name="modelo" value="<?php echo $producto['modelo']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo $producto['descripcion']; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="precio_unitario" class="form-label">Precio Unitario</label>
        <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" step="0.01" value="<?php echo $producto['precio_unitario']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="cantidad" class="form-label">Cantidad</label>
        <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo $producto['cantidad']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary" name="guardar_producto">Guardar Cambios</button>
</form>
    </div>
</body>
</html>
