<?php
//include file (but do we even need one since only defining the sqlite host and not pass/user like in lab 14a?)
//-yes we are goood just like this cacuse sql lite has operator system level validation ass good like this.
//i will still create the inc file which will contain the API you will need for the button to work. -june
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
<!---adding inline styling for flex for now later we can get crazy inside CSS aswell -June -->
<main style="display: flex;">
    <section style="width: 25%;">
        <h2>Customers</h2>

        <?php
        //connecting to DB
        try {
            //could use dbConnString(variable) instead, if using .inc file
            $db = new PDO('sqlite:data/stocks.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //adding user ID to the query inorder to attach the button with the user ID then we can use for redirecting. -June 10/5/2025
            $sql = "SELECT id,firstname, lastname FROM users ORDER BY lastname, firstname";
            $result = $db->query($sql, PDO::FETCH_ASSOC);

            echo "<table class = within>";

            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row["lastname"] . ", " . $row["firstname"] . "</td>";
                //adding the button in the table form. with the respective user and userID -June 10/05/2025
                echo "<td><a href='index.php?userid=" . $row["id"] . "'>Portfolio</a></td>";
                echo "</tr>";
            }

            echo "</table>";

        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
        ?>

    </section>
    <!--portfolio summary portion start -June-->
    <section style="width: 75%;">
        <?php
        //getting the id from the click then running the sql search for the specific user.
        if (isset($_GET["userid"])){
            $userID = $_GET["userid"];
            //remember user id in the portfolio table is the ID in the user table. -June
            try{
                //calling the associated functiions
                $data = getPortfolioData($userID); 
                //once the data is retrived show it to the user;
                //showPortfolioData($data);
            } catch (Exception $ex){//we all hate our ex'es :) - June
                echo "<p>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p>Please select a customer or else the code wont work</p>";
        }
        ?>
    </section>
    </main>
<?php
//global functoins start

function getPortfolioData($userID){
    //open connection to database
    $db = new pdo('sqlite:data/stocks.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // get the comapany data
    $comSqlQuery = "SELECT COUNT(DISTINCT symbol) FROM portfolio where userid = $userID";
    $comResult = $db->query($comSqlQuery);
    $totalCom = $comResult->fetchColumn();
    echo "<p> total number of shares = $totalCom </p>";

    //get the total shared owned shared by the userID
    $sumStockQueryString = "SELECT SUM(amount) FROM portfolio WHERE userId = $userID";
    $sumResult = $db->query($sumStockQueryString);
    $totalSumResult = $sumResult->fetchColumn();
    echo "<p> summation of the total shares:  $totalSumResult</p>";
}
?>
</body>
</html>