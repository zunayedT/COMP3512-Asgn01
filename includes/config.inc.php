<?php
//allows us to define our database connection in one place          
define('DBCONNSTRING', 'sqlite:' . __DIR__ . '/../data/stocks.db');
define ('DBUSER', '');
define ('DBPASS', '');

//helper class 
class DatabaseHelper {
    //returns a connection to the database
    public static function createConnection( $values=array() ) {
        $connString = $values[0];
        $user = $values[1];
        $password = $values[2];
        $pdo = new PDO($connString,$user,$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC);return $pdo;
 }
 public static function runQuery($connection, $sql, $parameters) {
     $statement = null;
     // do a prepared statement if there are params
     if (isset($parameters)) {
         // make sure params are in array
         if (!is_array($parameters)) {
             $parameters = array($parameters);
            }
            // uses a prepared statement if there are params
            $statement = $connection->prepare($sql);
            $executedOk = $statement->execute($parameters);
            if (! $executedOk) throw new PDOException;
        } else {
            // executes a normal query
            $statement = $connection->query($sql);
            if (!$statement) throw new PDOException;
        }
        return $statement;
    }

 public static function getPortfolioData($userID){
        //open connection to database
        $db = new pdo('sqlite:data/stocks.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // get the comapany data
        $comSqlQuery = "SELECT COUNT(DISTINCT symbol) FROM portfolio where userid = $userID";
        $comResult = $db->query($comSqlQuery);
        $totalCom = $comResult->fetchColumn();
        //echo "<p> total number of shares = $totalCom </p>"; debug echo -june

        //get the total shared owned shared by the userID
        $sumStockQueryString = "SELECT SUM(amount) FROM portfolio WHERE userId = $userID";
        $sumResult = $db->query($sumStockQueryString);
        $totalSumResult = $sumResult->fetchColumn();
        //echo "<p> summation of the total shares:  $totalSumResult</p>";   debug echo -june

        //Get details for the each stock holdings by the speicfic users
        $detailsSql = "
            SELECT p.symbol, s.name, s.sector, p.amount
            FROM portfolio p
            JOIN companies s ON p.symbol = s.symbol
            WHERE p.userId = ?
        ";
        $stmt = $db->prepare($detailsSql);
        $stmt->execute([$userID]);
        $portfolioRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Calculating value of each stock and total portfolio value
        $totalPortValue = 0;
        foreach ($portfolioRows as &$row) {
            $closeSql = "SELECT close FROM history WHERE symbol = ? ORDER BY date DESC LIMIT 1";
            $stmt = $db->prepare($closeSql);
            $stmt->execute([$row['symbol']]);
            $latestClose = (float)$stmt->fetchColumn();

            if ($latestClose === false) $latestClose = 0;

            $row['latest_close'] = $latestClose;
            $row['stock_value']  = $latestClose * $row['amount'];

            $totalPortValue += $row['stock_value'];
        }

        // Returning associative array
        return [
            "userId" => (int)$userID,
            "total_unique_companies" => $totalCom,
            "total_shares" => $totalSumResult,
            "portfolio" => $portfolioRows,
            "total_portfolio_value" => round($totalPortValue, 2)
        ];
    }

public static function getCompanyDetails($symbol){
    try {
        $dbPath = __DIR__ . '/../data/stocks.db';
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT * FROM companies WHERE symbol = :symbol");
        $stmt->bindValue(':symbol', $symbol);
        $stmt->execute();
        $company = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$company) return null;

        $financials = [];
        if (!empty($company['financials'])) {
            $financials = json_decode($company['financials'], true);
        }

        $stmt2 = $db->prepare("SELECT * FROM history WHERE symbol = :symbol ORDER BY date DESC LIMIT 90");
        $stmt2->bindValue(':symbol', $symbol);
        $stmt2->execute();
        $history = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $stmt3 = $db->prepare("
            SELECT MAX(high) AS maxHigh, MIN(low) AS minLow,
                   SUM(volume) AS totalVolume, AVG(volume) AS avgVolume
            FROM history WHERE symbol = :symbol");
        $stmt3->bindValue(':symbol', $symbol);
        $stmt3->execute();
        $stats = $stmt3->fetch(PDO::FETCH_ASSOC);

        return [
            'company'    => $company,
            'financials' => $financials,
            'history'    => $history,
            'stats'      => $stats
        ];

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
}
?>