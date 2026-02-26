<?php
$host = 'sql309.infinityfree.com'; // Seu Host do MySQL
$db   = 'if0_38314291_Salon_Registros';   // Seu Nome do Banco
$user = 'if0_38314291';               // Seu Usuário
$pass = 'jOsv3T5GIW1uBC';                // Sua Senha

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Erro de conexão com banco de dados']));
}
session_start();
?>