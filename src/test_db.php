<?php
require 'config/db.php';
$tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    echo "TABLE: $table\n";
    $stm = $pdo->query("DESCRIBE $table");
    $cols = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        echo "  " . $col['Field'] . " - " . $col['Type'] . "\n";
    }
}
?>