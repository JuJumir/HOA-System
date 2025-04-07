<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/functions.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        // Clear existing houses if needed
        // $pdo->exec("TRUNCATE TABLE Houses");
        
        // Generate 5 phases x 15 blocks x 15 lots = 1125 properties
        $phases = range(1, 5);
        $blocks = range(1, 15);
        $lots = range(1, 15);
        
        $stmt = $pdo->prepare("INSERT INTO Houses (address, phase, block, lot) VALUES (?, ?, ?, ?)");
        
        foreach ($phases as $phase) {
            foreach ($blocks as $block) {
                foreach ($lots as $lot) {
                    $address = "{$block}-{$lot} Sunshine Blvd";
                    $stmt->execute([$address, $phase, $block, $lot]);
                }
            }
        }
        
        $pdo->commit();
        $message = "Successfully generated " . (count($phases)*count($blocks)*count($lots)) . " properties!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate Properties</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Generate Properties</h1>
        
        <?php if (isset($message)): ?>
            <div class="<?= strpos($message, 'Error') !== false ? 'error' : 'success' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <p>This will create 5 phases × 15 blocks × 15 lots = 1,125 properties</p>
            <button type="submit" onclick="return confirm('This will create 1125 properties. Continue?')">
                Generate Properties
            </button>
        </form>
        
        <div class="nav">
            <a href="manage-houses.php">Back to Properties</a>
        </div>
    </div>
</body>
</html>