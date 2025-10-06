<?php
//include file (but do we even need one since only defining the sqlite host and not pass/user like in lab 14a?)
?>

<!DOCTYPE html>
<html lang=en>
<head>
    <title>Portfolio Project - Home Page</title>
    <meta charset=utf-8>
    <!--insert style links later -->
</head>
<body >

<header>
    <h1>Portfolio Project</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="apis.php">APIs</a>
    </nav>
    <hr>
</header>

<main>
    <section>
        <h2>Customers</h2>

        <?php
        //connecting to DB
        try {
            //could use dbConnString(variable) instead, if using .inc file
            $db = new PDO('sqlite:data/stocks.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "SELECT firstname, lastname FROM users ORDER BY lastname, firstname";
            $result = $db->query($sql, PDO::FETCH_ASSOC);

            echo "<table class = within>";

            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row["lastname"] . ", " . $row["firstname"] . "</td>";
                echo "</tr>";
            }

            echo "</table>";

        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
        ?>

    </section>
    </main>
</body>
</html>