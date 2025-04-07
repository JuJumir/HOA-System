<?php
require 'includes/db.php';
require 'includes/auth.php';
require 'includes/functions.php';

requireAuth();

$user = getCurrentUser();
$houseAddress = $user['house_id'] ? getHouseAddress($user['house_id']) : 'Not assigned';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?= sanitizeInput($user['name']) ?></h1>
        
        <div class="user-info">
            <p><strong>Email:</strong> <?= sanitizeInput($user['email']) ?></p>
            <p><strong>Role:</strong> <?= ucfirst($user['role']) ?></p>
            <p><strong>House:</strong> <?= sanitizeInput($houseAddress) ?></p>
            <p><strong>Status:</strong> <?= $user['is_verified'] ? 'Verified' : 'Pending Verification' ?></p>
        </div>
        
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="admin/verify.php" class="btn">Verify Users</a>
            <a href="admin/manage-houses.php" class="btn">manage Users</a>
            <a href="admin/import-houses.php" class="btn">import Users</a>
        <?php endif; ?>
        
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>
</html>