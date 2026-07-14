<?php
$host   = 'localhost';
$dbName = 'unefa_seguro'; 
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $error) {
    exit("Fallo crítico de infraestructura de red.");
}
?>
