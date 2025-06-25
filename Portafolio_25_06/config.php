<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

$host = "localhost";
$db = "allan_medina_db1";
$user = "allan_medina";
$pass = "allan_medina2025";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida"]));
}
?>