<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/functions.php';

requireAdmin();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'] ?? null;
$approve = $data['approve'] ?? false;

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit();
}

try {
    if ($approve) {
        // Approve user
        $stmt = $pdo->prepare("UPDATE Users SET is_verified = TRUE WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        // Mark house as occupied
        $stmt = $pdo->prepare("UPDATE Houses h
                              JOIN Users u ON h.house_id = u.house_id
                              SET h.is_occupied = TRUE
                              WHERE u.user_id = ?");
        $stmt->execute([$userId]);
        
        echo json_encode(['success' => true]);
    } else {
        // Reject - get document first
        $stmt = $pdo->prepare("SELECT verification_document FROM Users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $document = $stmt->fetchColumn();
        
        // Delete user
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        // Delete document
        if ($document && file_exists("../../uploads/verification/$document")) {
            unlink("../../uploads/verification/$document");
        }
        
        echo json_encode(['success' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>