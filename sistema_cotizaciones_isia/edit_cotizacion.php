<?php
session_start();
include('db.php');
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $cliente_id = $_POST['cliente_id'];
        $descripcion = $_POST['descripcion'];
        $fecha = $_POST['fecha'];
        $query = "UPDATE cotizaciones SET cliente_id = ?, descripcion = ?, fecha = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issi", $cliente_id, $descripcion, $fecha, $id);
        $stmt->execute();
        header('Location: cotizaciones.php');
    }
    $query = "SELECT * FROM cotizaciones WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cotizacion = $result->fetch_assoc();
} else {
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
    <title>Editar Cotización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Cotización</h1>
        <form method="POST" action="edit_cotizacion.php?id=<?php echo $cotizacion['id']; ?>">
            <div class="mb-3">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select class="form-select" id="cliente_id" name="cliente_id" required>
                    <option value="">Seleccione un cliente</option>
                    <?php 
                    $clientes = $conn->query("SELECT * FROM clientes");
                    while($row = $clientes->fetch_assoc()) {
                        $selected = ($row['id'] == $cotizacion['cliente_id']) ? 'selected' : '';
                        echo "<option value='".$row['id']."' $selected>".$row['nombre']." ".$row['apellido']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo $cotizacion['descripcion']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $cotizacion['fecha']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
