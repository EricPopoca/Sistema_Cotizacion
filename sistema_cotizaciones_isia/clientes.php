<?php
session_start();
include('db.php');
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $razon_social = $_POST['razon_social'];

    // Consulta para insertar el nuevo cliente
    $query = "INSERT INTO clientes (nombre, apellido, telefono, correo, razon_social) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Enlaza los parámetros correctamente
    $stmt->bind_param("sssss", $nombre, $apellido, $telefono, $correo, $razon_social);

   // if ($stmt->execute()) {
   //    echo "Cliente agregado correctamente.";
   // } else {
   //     echo "Error al agregar el cliente: " . $stmt->error;
   // }
}
$query = "SELECT * FROM clientes";
$result = $conn->query($query);
?>
<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Gestión de Clientes</h1>
        <form method="POST" action="clientes.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                 <label for="razon_social" class="form-label">Razón Social</label>
                 <input type="text" class="form-control" id="razon_social" name="razon_social" required>
            </div>

            <button type="submit" name="add_client" class="btn btn-primary">Añadir Cliente</button>
        </form>
        <h2 class="mt-5">Clientes Registrados</h2>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Razón Social</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['apellido']; ?></td>
                    <td><?php echo $row['telefono']; ?></td>
                    <td><?php echo $row['correo']; ?></td>
                    <td><?php echo $row['razon_social']; ?></td>
                    <td>
                        <a href="edit_cliente.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="delete_cliente.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
