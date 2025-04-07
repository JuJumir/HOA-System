<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'hoa');

if ($db->connect_error) {
    die("Database connection failed");
}

// Get user info
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM Users WHERE user_id = $user_id";
$result = $db->query($query);
$user = $result->fetch_assoc();

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HOA System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
        
        <div class="user-info">
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Role: <?php echo ucfirst(htmlspecialchars($user['role'])); ?></p>
        </div>
        
        <div class="nav">
            <a href="logout.php">Logout</a>
        </div>
        
        <p>This is your dashboard. More features will be added here based on your role.</p>
    </div>
</body>
</html> 