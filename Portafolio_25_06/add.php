<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap 5 Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>


<body>

<div class="container-fluid p-5 bg-primary text-white text-center">
  <h1>Lalaland</h1> 
</div>
  


</body>


<div class="d-flex justify-content-center ">
  
<?php
include 'auth.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $titulo = $_POST['titulo'];
  $descripcion = $_POST['descripcion'];
  $url_github = $_POST['url_github'];
  $url_produccion = $_POST['url_produccion'];

  $imagen = $_FILES['imagen']['name'];
  $tmp = $_FILES['imagen']['tmp_name'];
  move_uploaded_file($tmp, "uploads/$imagen");

  $sql = "INSERT INTO proyectos (titulo, descripcion, url_github, url_produccion, imagen) 
          VALUES ('$titulo', '$descripcion', '$url_github', '$url_produccion', '$imagen')";

  $conn->query($sql);
  header("Location: index.php");
}
?>

<form method="post" enctype="multipart/form-data">
  <input type="text" name="titulo" placeholder="Título" required><br>
  <textarea name="descripcion" maxlength="200" placeholder="Descripción (máx 200 palabras)" required></textarea><br>
  <input type="url" name="url_github" placeholder="URL GitHub"><br>
  <input type="url" name="url_produccion" placeholder="URL Producción"><br>
  <input type="file" name="imagen" required><br>
  <button type="submit">Guardar</button>
</form>

</div>