<?php
require 'includes/db.php';
require 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOA Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Our HOA System</h1>
        
        <p>Please login or register to access the system features.</p>
        
        <div class="nav">
            <a href="login.html">Login</a>
            <a href="register.html">Register</a>
        </div>
    </div>
</body>
</html>