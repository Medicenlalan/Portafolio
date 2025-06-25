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
    
    // Obtener el ID del proyecto a eliminar
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id > 0) {
        // Eliminar el proyecto
        $stmt = $conn->prepare("DELETE FROM proyectos WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Proyecto eliminado exitosamente
                header("Location: index.php?mensaje=eliminado");
            } else {
                // No se encontró el proyecto
                header("Location: index.php?mensaje=no_encontrado");
            }
        } else {
            // Error al eliminar
            header("Location: index.php?mensaje=error");
        }
        $stmt->close();
    } else {
        // ID inválido
        header("Location: index.php?mensaje=id_invalido");
    }
    
    $conn->close();
    
} catch (Exception $e) {
    // Error de base de datos
    header("Location: index.php?mensaje=error_db");
}
?>
  