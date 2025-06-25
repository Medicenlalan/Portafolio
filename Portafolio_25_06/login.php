<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login - Panel de Administración</title>
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
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="login.php">Login</a>
      </li>
    </ul>
  </div>
</nav>

<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows === 1) {
    $_SESSION['user'] = $username;
    $_SESSION['logged_in'] = true;
    header("Location: proyectos_display.php");
    exit();
  } else {
    $error_message = "Credenciales incorrectas.";
  }
}
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="card">
        <div class="card-header text-center">
          <h4>Panel de Administración</h4>
        </div>
        <div class="card-body">
          <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
          <?php endif; ?>
          
          <form method="post">
            <div class="mb-3">
              <label for="username" class="form-label">Usuario</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese su usuario" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </div>
          </form>
          
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>