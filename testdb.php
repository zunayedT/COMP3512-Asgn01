<?php
try {
    // Path is relative to this script; adjust if your path is different
    $db = new PDO('sqlite:data/stocks.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!<br>";

    // Optional: show table names for further verification
    $result = $db->query("SELECT name FROM companies;");
    foreach ($result as $row) {
        echo $row['name'] . "<br>";
    }
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>