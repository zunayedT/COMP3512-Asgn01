<?php
//include file (but do we even need one since only defining the sqlite host and not pass/user like in lab 14a?)
//-yes we are goood just like this cacuse sql lite has operator system level validation ass good like this.
//i will still create the inc file which will contain the API you will need for the button to work. -june
require_once __DIR__ . '/includes/config.inc.php';
require_once __DIR__ . '/includes/db-classes.inc.php';
?>

<!DOCTYPE html>
<html lang=en>
<head>
    <title>Portfolio Project - Home Page</title>
    <meta charset=utf-8>
    <link rel="stylesheet" href="assets/globalStyle.css">
    <link rel="stylesheet" href="assets/indexStyle.css">
</head>
<body >

<header>
    <h1>Portfolio Project</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="apis.php">APIs</a>
    </nav>
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
            $db = null;

        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
        ?>

    </section>
    <!--portfolio summary portion start -June-->
    <section style="width: 75%; display: grid;" id="portSumSection">
        <?php
        //getting the id from the click then running the sql search for the specific user.
        if (isset($_GET["userid"])){
            $userID = $_GET["userid"];
            //remember user id in the portfolio table is the ID in the user table. -June
            try{
                //calling the associated functiions
                $data = CompaniesDB::getPortfolioData($userID);
                showPortfolioData($data);
                //once the data is retrived show it to the user;
                //now its time to call the function to show company details using the method i wrote below. -June
               
                
            } catch (Exception $ex){//we all hate our ex'es :) - June
                echo "<p>Database error: " . $ex . "</p>";
            }
        } else {
            echo "<p>Please select a portfolio button corresponding to the clients name, in order to view portfolio. We really appriciate your bussiness with us.</p>";
        }
        ?>
    </section>
    </main>

<footer>
    <p>© <?php echo date("Y"); ?> COMP 3512 Assignment #1 | Mount Royal University</p>
</footer>
<?php

//global methods start - June
function showPortfolioData($result) {
    // Extract key values
    $totalCompanies = $result['total_unique_companies'] ?? 0;
    $totalShares = $result['total_shares'] ?? 0;
    $totalValue = $result['total_portfolio_value'] ?? 0;
    $portfolio = $result['portfolio'] ?? [];

    echo '
    <section>
      <h1>Portfolio Summary</h1>

      <div class="portfolio-summary-grid">
        <div class="summary-block">
          <div class="summary-label">Companies</div>
          <div class="summary-number">' . $totalCompanies . '</div>
          <div class="summary-helper">Count of records</div>
        </div>
        <div class="summary-block">
          <div class="summary-label"># Shares</div>
          <div class="summary-number">' . $totalShares . '</div>
          <div class="summary-helper">Sum of amount field</div>
        </div>
        <div class="summary-block">
          <div class="summary-label">Total Value</div>
          <div class="summary-number">$' . number_format($totalValue, 2) . '</div>
          <div class="summary-helper">Sum of stock values</div>
        </div>
      </div>

      <h2>Portfolio Details</h2>
      <table class="portfolio-table">
        <thead>
          <tr>
            <th>Symbol</th>
            <th>Name</th>
            <th>Sector</th>
            <th>Amount</th>
            <th>Value</th>
          </tr>
        </thead>
        <tbody>
    ';

    foreach ($portfolio as $row) {
        $symbol = htmlspecialchars($row['symbol']);
        $name = htmlspecialchars($row['name']);
        $sector = htmlspecialchars($row['sector']);
        $amount = (int)$row['amount'];
        $value = number_format($row['stock_value'], 2);
        echo "
          <tr>
            <td><a href='company.php?userid=" . $_GET['userid'] . "&ref=$symbol'>$symbol</a></td>
            <td><a href='company.php?userid=" . $_GET['userid'] . "&ref=$symbol'>$name</a></td>
            <td>$sector</td>
            <td>$amount</td>
            <td>\$$value</td>
          </tr>
        ";
    }

    echo '
        </tbody>
      </table>
    </section>
    ';
}
?>
</body>
</html>