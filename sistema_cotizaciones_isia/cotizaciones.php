<?php
session_start();
include('db.php');
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_cotizacion'])) {
        $cliente_id = $_POST['cliente_id'];
        $descripcion = $_POST['descripcion'];
        $fecha = $_POST['fecha'];
        $query = "INSERT INTO cotizaciones (cliente_id, descripcion, fecha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $cliente_id, $descripcion, $fecha);
        $stmt->execute();
    }
}
$query = "SELECT * FROM cotizaciones";
$result = $conn->query($query);
?>
<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Gestión de Cotizaciones</h1>
        <form method="POST" action="cotizaciones.php">
            <div class="mb-3">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select class="form-select" id="cliente_id" name="cliente_id" required>
                    <option value="">Seleccione un cliente</option>
                    <?php 
                    $clientes = $conn->query("SELECT * FROM clientes");
                    while($row = $clientes->fetch_assoc()) {
                        echo "<option value='".$row['id']."'>".$row['nombre']." ".$row['apellido']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <button type="submit" name="add_cotizacion" class="btn btn-primary">Añadir Cotización</button>
        </form>
        
        <h2 class="mt-5">Cotizaciones Registradas</h2>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php 
                        $cliente = $conn->query("SELECT * FROM clientes WHERE id = ".$row['cliente_id'])->fetch_assoc();
                        echo $cliente['nombre']." ".$cliente['apellido'];
                    ?></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td>
                        <a href="detalles_cotizacion.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Detalles</a>
                        <a href="edit_cotizacion.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="delete_cotizacion.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        <a href="formato_cotizacion.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm" target="_blank">Imprimir</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
