<?php
session_start();
include('db.php');

// Verifica si el administrador está logueado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Verifica que se haya pasado una cotización
if (isset($_GET['id'])) {
    $cotizacion_id = $_GET['id'];

    // Agregar producto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_producto'])) {
        $clave = $_POST['clave'];
        $modelo = $_POST['modelo'];
        $descripcion = $_POST['descripcion'];
        $precio_unitario = $_POST['precio_unitario'];
        $cantidad = $_POST['cantidad'];
        $importe = $precio_unitario * $cantidad;

        // Insertar producto en la base de datos
        $query = "INSERT INTO productos (cotizacion_id, clave, modelo, descripcion, precio_unitario, cantidad, importe) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssdid", $cotizacion_id, $clave, $modelo, $descripcion, $precio_unitario, $cantidad, $importe);
        $stmt->execute();
    }

    // Agregar característica
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_caracteristica'])) {
        $descripcion = $_POST['descripcion_caracteristica'];

        // Insertar característica en la base de datos
        $query = "INSERT INTO caracteristicas (cotizacion_id, descripcion) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $cotizacion_id, $descripcion);
        $stmt->execute();
    }
}

// Mostrar productos asociados a la cotización
$query_productos = "SELECT * FROM productos WHERE cotizacion_id = ?";
$stmt_productos = $conn->prepare($query_productos);
$stmt_productos->bind_param("i", $cotizacion_id);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

// Mostrar características asociadas a la cotización
$query_caracteristicas = "SELECT * FROM caracteristicas WHERE cotizacion_id = ?";
$stmt_caracteristicas = $conn->prepare($query_caracteristicas);
$stmt_caracteristicas->bind_param("i", $cotizacion_id);
$stmt_caracteristicas->execute();
$result_caracteristicas = $stmt_caracteristicas->get_result();
?>
<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Cotización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Detalles de la Cotización</h1>

        <!-- Formulario para agregar producto -->
        <h3>Agregar Producto</h3>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="clave" class="form-label">Clave</label>
                <input type="text" class="form-control" id="clave" name="clave" required>
            </div>
            <div class="mb-3">
                <label for="modelo" class="form-label">Modelo</label>
                <input type="text" class="form-control" id="modelo" name="modelo" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="mb-3">
                <label for="precio_unitario" class="form-label">Precio Unitario</label>
                <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_producto">Guardar Producto</button>
        </form>

        <!-- Mostrar productos -->
        <h3>Productos Registrados</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Modelo</th>
                    <th>Descripción</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Importe</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = $result_productos->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $producto['clave']; ?></td>
                    <td><?php echo $producto['modelo']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td><?php echo $producto['precio_unitario']; ?></td>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td><?php echo $producto['importe']; ?></td>
                    <td>
                        <!-- Botón de Editar -->
                        <a href="edit_producto.php?producto_id=<?php echo $producto['id']; ?>" class="btn btn-warning">Editar</a>
                        <!-- Botón de Eliminar -->
                        <a href="delete_producto.php?producto_id=<?php echo $producto['id']; ?>&cotizacion_id=<?php echo $cotizacion_id; ?>" class="btn btn-danger">Eliminar</a>

                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Formulario para agregar característica -->
        <h3>Agregar Característica</h3>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="descripcion_caracteristica" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion_caracteristica" name="descripcion_caracteristica" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="add_caracteristica">Guardar Característica</button>
        </form>

        <!-- Mostrar características -->
        <h3>Características Registradas</h3>
        <ul>
            <?php while ($caracteristica = $result_caracteristicas->fetch_assoc()) : ?>
                <li><?php echo $caracteristica['descripcion']; ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>
