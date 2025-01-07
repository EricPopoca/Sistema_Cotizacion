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
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $razon_social = $_POST['razon_social'];
        // Corregir la consulta SQL, eliminando la coma extra
        $query = "UPDATE clientes SET nombre = ?, apellido = ?, telefono = ?, correo = ?, razon_social = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $nombre, $apellido, $telefono, $correo, $razon_social, $id);
        $stmt->execute();
        header('Location: clientes.php');
    }
    $query = "SELECT * FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
} else {
    header('Location: clientes.php');
    exit;
}
?>
<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Cliente</h1>
        <form method="POST" action="edit_cliente.php?id=<?php echo $cliente['id']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $cliente['nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $cliente['apellido']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $cliente['telefono']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $cliente['correo']; ?>" required>
            </div>
            <div class="mb-3">
                 <label for="razon_social" class="form-label">Razón Social</label>
                 <input type="text" class="form-control" id="razon_social" name="razon_social" value="<?php echo $cliente['razon_social']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
