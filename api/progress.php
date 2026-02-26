<?php
require 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Não autorizado']));
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

// 1. Salvar Progresso (Quando termina uma lição)
if ($action === 'save_progress') {
    $courseId = $_POST['course_id'];
    $lessonId = $_POST['lesson_id'];
    $score = $_POST['score'];

    // Verifica se já existe para atualizar, ou insere novo
    $sql = "INSERT INTO user_progress (user_id, course_id, lesson_id, score) 
            VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE score = VALUES(score), completed_at = CURRENT_TIMESTAMP";
            
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$userId, $courseId, $lessonId, $score])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}

// 2. Carregar Progresso (Ao fazer login)
if ($action === 'get_progress') {
    $stmt = $pdo->prepare("SELECT lesson_id, score FROM user_progress WHERE user_id = ?");
    $stmt->execute([$userId]);
    $progress = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retorna array simples
    echo json_encode(['success' => true, 'data' => $progress]);
}

// 3. Carregar Certificados
if ($action === 'get_certificates') {
    $stmt = $pdo->prepare("SELECT course_title, file_path, issued_at FROM certificates WHERE user_id = ?");
    $stmt->execute([$userId]);
    $certs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $certs]);
}
?>