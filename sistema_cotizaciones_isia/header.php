<!-- header.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Sistema De Cotizaciones ISIA</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="clientes.php">Clientes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cotizaciones.php">Cotizaciones</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="ajustes.php">Ajustes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Agregar estilos personalizados -->
<style>
  /* Cambiar el color de fondo de la navbar */
  .navbar {
    background-color: #004c8c; /* Azul oscuro */
  }

  /* Estilo para el texto de los links */
  .navbar-nav .nav-link {
    color: #fff !important;
  }

  /* Cambiar color del link al pasar el mouse */
  .navbar-nav .nav-link:hover {
    color: #f8f9fa !important;
  }

  /* Logo con tamaño personalizado */
  .navbar-brand {
    font-weight: bold;
    color: #fff !important;
  }

  /* Botón de la navbar para pantallas pequeñas */
  .navbar-toggler-icon {
    background-color: #fff;
  }

  /* Estilo para el navbar cuando está colapsado */
  .navbar-collapse {
    text-align: center;
  }
</style>
