<?php
require_once __DIR__ . '/config/db.php';
try {
    $stmt = $pdo->query("DESCRIBE students");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
