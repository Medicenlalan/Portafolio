<?php
$host = "localhost";
$db = "allan_medina_db1";
$user = "allan_medina";
$pass = "allan_medina2025";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
?>
