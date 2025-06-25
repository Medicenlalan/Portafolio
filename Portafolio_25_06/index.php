<?php
session_start();
include 'db.php';

// Obtener proyectos de la base de datos
$result = $conn->query("SELECT * FROM proyectos ORDER BY created_at DESC");

// Procesar mensajes de confirmación
$mensaje = '';
$tipo_mensaje = '';
if (isset($_GET['mensaje'])) {
    switch ($_GET['mensaje']) {
        case 'eliminado':
            $mensaje = 'Proyecto eliminado exitosamente.';
            $tipo_mensaje = 'success';
            break;
        case 'no_encontrado':
            $mensaje = 'El proyecto no fue encontrado.';
            $tipo_mensaje = 'warning';
            break;
        case 'error':
            $mensaje = 'Error al eliminar el proyecto.';
            $tipo_mensaje = 'danger';
            break;
        case 'id_invalido':
            $mensaje = 'ID de proyecto inválido.';
            $tipo_mensaje = 'danger';
            break;
        case 'error_db':
            $mensaje = 'Error de base de datos.';
            $tipo_mensaje = 'danger';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <title>Mi Portafolio</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="Assets/Style.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Mi Portafolio</a>
    <ul class="navbar-nav me-auto">
      <li class="nav-item">
        <a class="nav-link active" href="index.php">Inicio</a>
      </li>
    </ul>
    <ul class="navbar-nav">
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <li class="nav-item">
          <span class="navbar-text me-3">Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="proyectos_display.php">Panel Admin</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Cerrar Sesión</a>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<?php if ($mensaje): ?>
<div class="container mt-3">
  <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
    <?php echo htmlspecialchars($mensaje); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-8 mx-auto text-center">
      <h1 class="display-4 mb-4">Bienvenido a Mi Portafolio</h1>
      <p class="lead mb-5">Soy Allan Medina, ahora estudiante de la Universidad Católica de Temuco, en la carrera Tecnica en Informática.
        Cuento con conocimientos en HTML, CSS, PHP, MySQL, y tambien en python y c#.

      </p>
    </div>
  </div>
</div>

<div class="container-fluid p-5 text-center">
  <h2>Mis Proyectos</h2>
</div>

<div class="container mt-4">
  <?php if ($result && $result->num_rows > 0): ?>
    <div class="row">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-12 col-md-6 col-lg-4 mb-4">
          <div class="card h-100">
            <?php if (!empty($row['imagen'])): ?>
              <img src="<?php echo htmlspecialchars($row['imagen']); ?>" 
                   class="card-img-top" alt="<?php echo htmlspecialchars($row['titulo']); ?>"
                   style="height: 200px; object-fit: cover;">
            <?php else: ?>
              <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                   style="height: 200px;">
                <i class="fas fa-code fa-3x text-white"></i>
              </div>
            <?php endif; ?>
            
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($row['titulo']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($row['descripcion']); ?></p>
              
              <div class="d-flex gap-2 mb-3">
                <?php if (!empty($row['url_github'])): ?>
                  <a href="<?php echo htmlspecialchars($row['url_github']); ?>" 
                     class="btn btn-outline-success btn-sm" target="_blank">
                    <i class="fab fa-github"></i> GitHub
                  </a>
                <?php endif; ?>
                
                <?php if (!empty($row['url_produccion'])): ?>
                  <a href="<?php echo htmlspecialchars($row['url_produccion']); ?>" 
                     class="btn btn-outline-primary btn-sm" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Ver Demo
                  </a>
                <?php endif; ?>
              </div>
              
              <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <div class="d-flex gap-2">
                  <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Editar
                  </a>
                  <a href="delete.php?id=<?php echo $row['id']; ?>" 
                     class="btn btn-danger btn-sm" 
                     onclick="return confirm('¿Estás seguro de que quieres eliminar el proyecto: <?php echo htmlspecialchars($row['titulo']); ?>?')">
                    <i class="fas fa-trash"></i> Eliminar
                  </a>
                </div>
              <?php endif; ?>
            </div>
            
            <div class="card-footer text-muted">
              <small>Creado: <?php echo date('d/m/Y', strtotime($row['created_at'])); ?></small>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">
      <h4>No hay proyectos disponibles</h4>
      <p>Los proyectos aparecerán aquí cuando sean agregados.</p>
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <a href="proyectos_display.php" class="btn btn-primary">Ir al Panel de Administración</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Reproductor de audio -->
<div class="text-center mt-4">
  <audio id="reproductor" controls>
    <source src="Assets/musica/01deenero.mp3" type="audio/mpeg">
    Tu navegador no soporta el elemento de audio.
  </audio>
</div>

<script>
  const reproductor = document.getElementById('reproductor');
  
  // Función para reproducir o pausar el audio
  function toggleAudio() {
    if (reproductor.paused) {
      reproductor.play();
    } else {
      reproductor.pause();
    }
  }

  // Agrega un evento de clic al título para controlar la reproducción
  const titulo = document.querySelector('h1');
  if (titulo) {
    titulo.style.cursor = 'pointer';
    titulo.title = 'Haz clic para reproducir/pausar música';
    titulo.addEventListener('click', toggleAudio);
  }
  
  // También puedes agregar el evento a otros elementos
  const subtitulo = document.querySelector('h2');
  if (subtitulo) {
    subtitulo.style.cursor = 'pointer';
    subtitulo.title = 'Haz clic para reproducir/pausar música';
    subtitulo.addEventListener('click', toggleAudio);
  }
</script>

</body>
</html>
    