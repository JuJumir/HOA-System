<?php
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

function getHouseAddress($house_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT address FROM Houses WHERE house_id = ?");
    $stmt->execute([$house_id]);
    return $stmt->fetchColumn();
}
?>