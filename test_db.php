<?php
require 'db.php';

try {
    $stmt = $pdo->query("SELECT NOW()");
    $row = $stmt->fetch();
    echo "Database connection successful!<br>";
    echo "Current MySQL time: " . $row[0];
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}
?>