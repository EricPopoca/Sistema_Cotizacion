<?php
// Iniciar sesión y conectarse a la base de datos
session_start();
include('db.php');
require_once('vendor/autoload.php'); // Asegúrate de haber instalado TCPDF o FPDF, dependiendo de la librería que uses

// Verificar si hay una cotización seleccionada
if (isset($_GET['id'])) {
    $cotizacion_id = $_GET['id'];

    // Obtener los detalles de la cotización
    $query = "SELECT * FROM cotizaciones WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cotizacion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cotizacion = $result->fetch_assoc();

    // Obtener los datos del cliente asociado
    $cliente_query = "SELECT * FROM clientes WHERE id = ?";
    $cliente_stmt = $conn->prepare($cliente_query);
    $cliente_stmt->bind_param("i", $cotizacion['cliente_id']);
    $cliente_stmt->execute();
    $cliente_result = $cliente_stmt->get_result();
    $cliente = $cliente_result->fetch_assoc();

    // Obtener productos asociados a la cotización
    $productos_query = "SELECT * FROM productos WHERE cotizacion_id = ?";
    $productos_stmt = $conn->prepare($productos_query);
    $productos_stmt->bind_param("i", $cotizacion_id);
    $productos_stmt->execute();
    $productos_result = $productos_stmt->get_result();

    // Obtener características asociadas a la cotización
    $caracteristicas_query = "SELECT * FROM caracteristicas WHERE cotizacion_id = ?";
    $caracteristicas_stmt = $conn->prepare($caracteristicas_query);
    $caracteristicas_stmt->bind_param("i", $cotizacion_id);
    $caracteristicas_stmt->execute();
    $caracteristicas_result = $caracteristicas_stmt->get_result();

    // Obtener ajustes de la empresa
    $ajustes_query = "SELECT * FROM ajustes_empresa";
    $ajustes_result = $conn->query($ajustes_query);
    $ajustes = $ajustes_result->fetch_assoc();
} else {
    die("Cotización no encontrada.");
}

// Generar PDF usando TCPDF (o FPDF si prefieres)
$pdf = new TCPDF();
$pdf->AddPage();

// Título
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Formato de Cotización', 0, 1, 'C');
$pdf->Ln(5);

// Datos del cliente
$pdf->SetFont('Helvetica', '', 12);
$pdf->Cell(100, 10, 'Cliente: ' . $cliente['nombre'] . ' ' . $cliente['apellido']);
$pdf->Ln();
$pdf->Cell(100, 10, 'Servicio: ' . $cotizacion['descripcion']);
$pdf->Ln();
$pdf->Cell(100, 10, 'Razon Social: ' . $cliente['razon_social']);
$pdf->Ln();
$pdf->Cell(100, 10, 'Correo: ' . $cliente['correo']);
$pdf->Ln();
$pdf->Cell(100, 10, 'Fecha de Cotización: ' . $cotizacion['fecha']);
$pdf->Ln(10);

// Detalles de la cotización (productos)
$pdf->Cell(0, 10, 'Productos:', 0, 1, 'L');
$pdf->SetFont('Helvetica', '', 10);
$pdf->Cell(40, 10, 'Producto', 1, 0, 'C');
$pdf->Cell(30, 10, 'Precio Unitario', 1, 0, 'C');
$pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
$pdf->Cell(40, 10, 'Importe', 1, 1, 'C');

while ($producto = $productos_result->fetch_assoc()) {
    $pdf->Cell(40, 10, $producto['descripcion'], 1);
    $pdf->Cell(30, 10, '$' . number_format($producto['precio_unitario'], 2), 1);
    $pdf->Cell(30, 10, $producto['cantidad'], 1);
    $pdf->Cell(40, 10, '$' . number_format($producto['importe'], 2), 1, 1, 'C');
}

// Características
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Características:', 0, 1, 'L');
while ($caracteristica = $caracteristicas_result->fetch_assoc()) {
    $pdf->Cell(0, 10, '* ' . $caracteristica['descripcion'], 0, 1);
}

// Información de la empresa desde ajustes
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Información de la Empresa:', 0, 1, 'L');
$pdf->Cell(0, 10, 'Nombre: ' . $ajustes['empresa_nombre'], 0, 1);
$pdf->Cell(0, 10, 'Mensaje de Bienvenida: ' . $ajustes['mensaje_bienvenida'], 0, 1);
$pdf->Cell(0, 10, 'Mensaje de Agradecimiento: ' . $ajustes['mensaje_agradecimiento'], 0, 1);

// Botón de Imprimir
$pdf->SetFont('Helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Imprimir', 0, 1, 'C');

$pdf->Output('I', 'cotizacion.pdf');

// HTML para mostrar la vista previa (opcional si se necesita la vista en página web)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato de Cotización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function printPDF() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Formato de Cotización</h1>
        <h3>Datos del Cliente</h3>
        <p>Cliente: <?php echo $cliente['nombre'] . ' ' . $cliente['apellido']; ?></p>
        <p>Correo: <?php echo $cliente['correo']; ?></p>

        <h3>Productos</h3>
        <ul>
            <?php while ($producto = $productos_result->fetch_assoc()) : ?>
                <li><?php echo $producto['descripcion'] . ' - ' . '$' . number_format($producto['precio_unitario'], 2); ?></li>
            <?php endwhile; ?>
        </ul>

        <button onclick="printPDF()" class="btn btn-primary">Imprimir Cotización</button>
    </div>
</body>
</html>
