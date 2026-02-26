<?php
require 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false]));
}

if (isset($_FILES['pdf']) && isset($_POST['course_title'])) {
    $userId = $_SESSION['user_id'];
    $courseTitle = $_POST['course_title'];
    
    // Nome único para o PDF
    $fileName = "cert_" . $userId . "_" . time() . ".pdf";
    $path = "../pdfs/" . $fileName;

    if (move_uploaded_file($_FILES['pdf']['tmp_name'], $path)) {
        // Salva referência no banco
        $stmt = $pdo->prepare("INSERT INTO certificates (user_id, course_title, file_path) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $courseTitle, $fileName]);
        
        echo json_encode(['success' => true, 'file' => $fileName]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Falha ao mover arquivo']);
    }
}
?>