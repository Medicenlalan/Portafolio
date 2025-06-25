<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Incluir configuración de base de datos
$host = "localhost";
$db = "allan_medina_db1";
$user = "allan_medina";
$pass = "allan_medina2025";

try {
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Obtener todos los proyectos
    $res = $conn->query("SELECT * FROM proyectos ORDER BY created_at DESC");
    if (!$res) {
        throw new Exception("Error en la consulta: " . $conn->error);
    }
    
    $proyectos = [];
    while ($row = $res->fetch_assoc()) {
        $proyectos[] = $row;
    }
    
} catch (Exception $e) {
    $error_message = $e->getMessage();
    $proyectos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Panel de Administración - Proyectos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="Assets/Style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Mi Portafolio</a>
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="proyectos_display.php">Panel Admin</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="navbar-text me-3">Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <h4>Error de Base de Datos</h4>
                <p><?php echo htmlspecialchars($error_message); ?></p>
                <p>Verifica que la base de datos esté configurada correctamente.</p>
            </div>
        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Panel de Administración - Proyectos</h1>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarProyectoModal">
                    <i class="fas fa-plus"></i> Agregar Proyecto
                </button>
            </div>
            
            <?php if (empty($proyectos)): ?>
                <div class="alert alert-info text-center">
                    <h4>No hay proyectos disponibles</h4>
                    <p>Haz clic en "Agregar Proyecto" para comenzar.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($proyectos as $proyecto): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <?php if (!empty($proyecto['imagen'])): ?>
                                    <img src="<?php echo htmlspecialchars($proyecto['imagen']); ?>" 
                                         class="card-img-top" alt="<?php echo htmlspecialchars($proyecto['titulo']); ?>"
                                         style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-code fa-3x text-white"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($proyecto['titulo']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($proyecto['descripcion']); ?></p>
                                    
                                    <div class="d-flex gap-2 mb-3">
                                        <?php if (!empty($proyecto['url_github'])): ?>
                                            <a href="<?php echo htmlspecialchars($proyecto['url_github']); ?>" 
                                               class="btn btn-outline-success btn-sm" target="_blank">
                                                <i class="fab fa-github"></i> GitHub
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($proyecto['url_produccion'])): ?>
                                            <a href="<?php echo htmlspecialchars($proyecto['url_produccion']); ?>" 
                                               class="btn btn-outline-primary btn-sm" target="_blank">
                                                <i class="fas fa-external-link-alt"></i> Ver Demo
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-warning btn-sm" onclick="editarProyecto(<?php echo $proyecto['id']; ?>)">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="eliminarProyecto(<?php echo $proyecto['id']; ?>)">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="card-footer text-muted">
                                    <small>ID: <?php echo $proyecto['id']; ?> | Creado: <?php echo date('d/m/Y', strtotime($proyecto['created_at'])); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Modal para agregar proyecto -->
    <div class="modal fade" id="agregarProyectoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Proyecto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formAgregarProyecto">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="url_github" class="form-label">URL GitHub</label>
                            <input type="url" class="form-control" id="url_github" name="url_github">
                        </div>
                        <div class="mb-3">
                            <label for="url_produccion" class="form-label">URL Producción</label>
                            <input type="url" class="form-control" id="url_produccion" name="url_produccion">
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">URL de la Imagen</label>
                            <input type="url" class="form-control" id="imagen" name="imagen">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Agregar Proyecto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script>
        // Función para agregar proyecto
        document.getElementById('formAgregarProyecto').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            fetch('proyectos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Proyecto agregado exitosamente');
                    location.reload();
                } else {
                    alert('Error al agregar proyecto');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al agregar proyecto');
            });
        });

        // Función para eliminar proyecto
        function eliminarProyecto(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este proyecto?')) {
                fetch(`proyectos.php/${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Proyecto eliminado exitosamente');
                        location.reload();
                    } else {
                        alert('Error al eliminar proyecto');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar proyecto');
                });
            }
        }

        // Función para editar proyecto (placeholder)
        function editarProyecto(id) {
            alert('Función de edición en desarrollo. ID del proyecto: ' + id);
        }
    </script>
</body>
</html> 