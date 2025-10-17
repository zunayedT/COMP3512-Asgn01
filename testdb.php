<?php
try {
    $db = new PDO('sqlite:data/stocks.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!<br>";
    //even after the databse is connected still new to check if i can acces the table. Its being long time i wrote a proper sql command. -June 10/5/2025
    $result = $db->query("SELECT name FROM companies order by name");
    foreach ($result as $row) {
        echo $row['name'] . "<br>";
    }
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
