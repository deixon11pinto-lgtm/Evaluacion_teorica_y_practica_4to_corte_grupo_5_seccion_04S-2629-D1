<?php

session_start();


require_once 'conexion.php'; 

$error_message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario_ingresado = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $password_ingresada = isset($_POST['clave']) ? $_POST['clave'] : '';

    if (empty($usuario_ingresado) || empty($password_ingresada)) {
        $error_message = "Todos los campos son obligatorios en el Back-end.";
    } 
    else {
        try {
            $query = "SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1";
            $stmt = $pdo->prepare($query);
            
            $stmt->bindParam(':usuario', $usuario_ingresado, PDO::PARAM_STR);
            $stmt->execute();
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {

                $hash_en_base_de_datos = $usuario['clave_hash']; 

                if (password_verify($password_ingresada, $hash_en_base_de_datos)) {
    
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_login'] = $usuario['usuario'];
                    $_SESSION['nombre_usuario'] = $usuario['nombre_completo'];
                    
                    echo "Autenticación exitosa. Bienvenido " . htmlspecialchars($usuario['nombre_completo']);
                    exit();
                } else {
                   
                    $error_message = "Credenciales de acceso incorrectas.";
                }
            } else {
                $error_message = "Credenciales de acceso incorrectas.";
            }

        } catch (PDOException $e) {
            
            error_log("Fallo crítico en Login de BD: " . $e->getMessage());
            $error_message = "Fallo perimetral de infraestructura temporal.";
        }
    }
}

if (!empty($error_message)) {
    echo "<h3>" . htmlspecialchars($error_message) . "</h3>";
}
?>
