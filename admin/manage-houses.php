<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/functions.php';

requireAdmin();

// Add new house
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    
    if (!empty($address)) {
        $stmt = $pdo->prepare("INSERT INTO Houses (address) VALUES (?)");
        $stmt->execute([$address]);
        header("Location: manage-houses.php?success=1");
        exit();
    }
}

// Get all houses
$stmt = $pdo->query("SELECT * FROM Houses ORDER BY address");
$houses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Houses</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Manage Properties</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success">House added successfully!</div>
        <?php endif; ?>
        
        <h2>Add New Property</h2>
        <form method="POST">
            <div class="form-group">
                <label>Full Address:</label>
                <input type="text" name="address" required>
            </div>
            <button type="submit">Add Property</button>
        </form>
        
        <h2>Existing Properties</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Address</th>
                <th>Status</th>
            </tr>
            <?php foreach ($houses as $house): ?>
            <tr>
                <td><?= $house['house_id'] ?></td>
                <td><?= htmlspecialchars($house['address']) ?></td>
                <td><?= $house['is_occupied'] ? 'Occupied' : 'Available' ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <div class="nav">
            <a href="../dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>