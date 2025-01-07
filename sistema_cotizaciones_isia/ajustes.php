<?php 
session_start();
include('db.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Verificar si se ha enviado el formulario de actualización de ajustes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_settings'])) {
        // Obtener los valores del formulario
        $empresa_nombre = $_POST['empresa_nombre'];
        $mensaje_bienvenida = $_POST['mensaje_bienvenida'];
        $mensaje_agradecimiento = $_POST['mensaje_agradecimiento'];

        // Manejo del archivo de logo (si se sube uno nuevo)
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $logo = $_FILES['logo']['name'];
            // Mover el archivo al directorio 'images'
            move_uploaded_file($_FILES['logo']['tmp_name'], "images/".$logo);
        } else {
            // Si no se sube archivo, mantener el logo actual
            $logo = ''; // O puedes poner el nombre del logo actual si lo tienes almacenado en la base de datos.
        }

        // Consulta para actualizar los ajustes en la base de datos
        $query = "UPDATE ajustes_empresa SET empresa_nombre = ?, mensaje_bienvenida = ?, mensaje_agradecimiento = ?, logo_empresa = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $empresa_nombre, $mensaje_bienvenida, $mensaje_agradecimiento, $logo);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Ajustes guardados correctamente.";
        } else {
            echo "Error al guardar los ajustes: " . $stmt->error;
        }
    }
}

// Consulta para obtener los ajustes actuales
$query = "SELECT * FROM ajustes_empresa LIMIT 1";
$result = $conn->query($query);
$ajustes = $result->fetch_assoc();
?>

<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajustes de Empresa</h1>

        <!-- Formulario para actualizar los ajustes -->
        <form method="POST" enctype="multipart/form-data" action="ajustes.php">
            <div class="mb-3">
                <label for="empresa_nombre" class="form-label">Nombre de la Empresa</label>
                <input type="text" class="form-control" id="empresa_nombre" name="empresa_nombre" value="<?php echo $ajustes['empresa_nombre'] ?? ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="mensaje_bienvenida" class="form-label">Mensaje de Bienvenida</label>
                <textarea class="form-control" id="mensaje_bienvenida" name="mensaje_bienvenida" required><?php echo $ajustes['mensaje_bienvenida'] ?? ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="mensaje_agradecimiento" class="form-label">Mensaje de Agradecimiento</label>
                <textarea class="form-control" id="mensaje_agradecimiento" name="mensaje_agradecimiento" required><?php echo $ajustes['mensaje_agradecimiento'] ?? ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="logo" class="form-label">Logo de la Empresa</label>
                <input type="file" class="form-control" id="logo" name="logo">
                <!-- Mostrar el logo actual si existe -->
                <?php if (!empty($ajustes['logo_empresa'])): ?>
                    <img src="images/<?php echo $ajustes['logo_empresa']; ?>" alt="Logo actual" width="100" class="mt-3">
                <?php endif; ?>
            </div>
            <button type="submit" name="update_settings" class="btn btn-primary">Guardar Ajustes</button>
        </form>
    </div>
</body>
</html>
