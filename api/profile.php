<?php
require 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Não autorizado']));
}

$userId = $_SESSION['user_id'];
$full_name = $_POST['full_name'];
$phone = $_POST['phone'];
$bi = $_POST['bi'];
$address = $_POST['address'];

// Atualiza dados de texto
$sql = "UPDATE users SET full_name=?, phone=?, bi_number=?, address=? WHERE id=?";
$params = [$full_name, $phone, $bi, $address, $userId];

// Lógica de Upload de Foto
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($ext, $allowed)) {
        $new_name = "user_" . $userId . "_" . time() . "." . $ext;
        $upload_dir = "../uploads/";
        
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_dir . $new_name)) {
            // Atualiza também o campo da foto no banco
            $sql = "UPDATE users SET full_name=?, phone=?, bi_number=?, address=?, profile_pic=? WHERE id=?";
            $params = [$full_name, $phone, $bi, $address, $new_name, $userId];
        }
    }
}

$stmt = $pdo->prepare($sql);
if ($stmt->execute($params)) {
    // Retorna os dados atualizados
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    echo json_encode(['success' => true, 'user' => $stmt->fetch(PDO::FETCH_ASSOC)]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar']);
}
?>