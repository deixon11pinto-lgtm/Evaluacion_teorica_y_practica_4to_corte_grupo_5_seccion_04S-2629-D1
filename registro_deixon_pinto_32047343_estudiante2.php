<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre  = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $clave   = isset($_POST['clave']) ? trim($_POST['clave']) : '';

    if (empty($nombre) || empty($usuario) || empty($clave)) {
        exit("Error 400: Datos malformados o incompletos.");
    }

    $clave_segura = password_hash($clave, PASSWORD_BCRYPT);

    try {
        $sql = "INSERT INTO usuarios (nombre_completo, usuario, clave_hash) VALUES (?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([$nombre, $usuario, $clave_segura]);

        echo "Registro exitoso en la capa de persistencia.";

    } catch (PDOException $error) {
        if ($error->getCode() == 23000) {
            echo "Error de integridad: El nombre de usuario ya se encuentra registrado.";
        } else {
            exit("Fallo DML en el servidor.");
        }
    }
}
?>
