<?php
include 'config.php';

// Proyectos de ejemplo
$proyectos_ejemplo = [
    [
        'titulo' => 'Sitio Web E-commerce',
        'descripcion' => 'Plataforma de comercio electrónico desarrollada con PHP y MySQL. Incluye sistema de carrito de compras, gestión de usuarios y panel de administración.',
        'url_github' => 'https://github.com/usuario/ecommerce',
        'url_produccion' => 'https://ecommerce-ejemplo.com',
        'imagen' => 'https://via.placeholder.com/400x200/1a1a1a/00ff00?text=E-commerce'
    ],
    [
        'titulo' => 'Aplicación de Gestión de Tareas',
        'descripcion' => 'Aplicación web para gestionar tareas y proyectos. Desarrollada con JavaScript, HTML5 y CSS3. Incluye funcionalidades de arrastrar y soltar.',
        'url_github' => 'https://github.com/usuario/task-manager',
        'url_produccion' => 'https://task-manager-demo.com',
        'imagen' => 'https://via.placeholder.com/400x200/1a1a1a/00ff00?text=Task+Manager'
    ],
    [
        'titulo' => 'Blog Personal',
        'descripcion' => 'Blog personal desarrollado con WordPress. Diseño responsive y optimizado para SEO. Incluye sistema de comentarios y categorías.',
        'url_github' => 'https://github.com/usuario/personal-blog',
        'url_produccion' => 'https://mi-blog-personal.com',
        'imagen' => 'https://via.placeholder.com/400x200/1a1a1a/00ff00?text=Blog+Personal'
    ]
];

// Insertar proyectos de ejemplo
$stmt = $conn->prepare("INSERT INTO proyectos (titulo, descripcion, url_github, url_produccion, imagen) VALUES (?, ?, ?, ?, ?)");

foreach ($proyectos_ejemplo as $proyecto) {
    $stmt->bind_param("sssss", 
        $proyecto['titulo'],
        $proyecto['descripcion'],
        $proyecto['url_github'],
        $proyecto['url_produccion'],
        $proyecto['imagen']
    );
    
    if ($stmt->execute()) {
        echo "Proyecto '{$proyecto['titulo']}' agregado exitosamente.<br>";
    } else {
        echo "Error al agregar proyecto '{$proyecto['titulo']}': " . $stmt->error . "<br>";
    }
}

$stmt->close();
$conn->close();

echo "<br><a href='proyectos_display.php'>Ver proyectos</a>";
?> 