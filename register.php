<?php
require 'includes/db.php';
require 'includes/auth.php';
$stmt = $pdo->query("SELECT house_id, address FROM Houses WHERE is_occupied = FALSE");
$availableHouses = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $house_id = $_POST['house_id'] ?? null;
    $document = $_FILES['verification_document'] ?? null;

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($house_id) || empty($document)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }

    // Check house exists
    $stmt = $pdo->prepare("SELECT house_id FROM Houses WHERE house_id = ?");
    $stmt->execute([$house_id]);
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid house ID']);
        exit();
    }

    // Handle file upload
    $uploadDir = 'uploads/verification/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    
    $fileName = uniqid() . '_' . basename($document['name']);
    $targetPath = $uploadDir . $fileName;

    if (!move_uploaded_file($document['tmp_name'], $targetPath)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload document']);
        exit();
    }

    // Create user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO Users (name, email, password, role, house_id, verification_document) 
                              VALUES (?, ?, ?, 'homeowner', ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword, $house_id, $fileName]);
        
        echo json_encode(['success' => true, 'message' => 'Registration submitted for verification']);
    } catch (PDOException $e) {
        @unlink($targetPath);
        $message = $e->getCode() == 23000 ? 'Email already registered' : 'Registration failed';
        echo json_encode(['success' => false, 'message' => $message]);
    }
}
?>