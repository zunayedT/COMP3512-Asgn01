<?php
//This include file consists of all DB Classes and Helper Class
//gateway class creation
//companies class
class CompaniesDB {
    private $pdo;
    private static $baseSQL = "SELECT symbol, name, sector FROM companies ";
 
    public function __construct($pdo) { 
        $this->pdo = $pdo; 
    }
 
    //returns all companies/stocks
    public function getAll() {
       $sql = self::$baseSQL . " ORDER BY symbol";
       $statement = DatabaseHelper::runQuery($this->pdo, $sql, null);
       return $statement->fetchAll();
    }
 
    //returns specific company/stock
    public function getOneBySymbol($symbol) {
       $sql = self::$baseSQL . " WHERE symbol LIKE ?"; //used 'LIKE' so that its not case sensistive -June
       $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($symbol));
       return $statement->fetchAll();
    }

    // this method is reponsible only for extacting data regarding a customer about their portfolio - June
 public static function getPortfolioData($userID){
        
        $db = DatabaseHelper::createConnection([DBCONNSTRING, DBUSER, DBPASS]);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // get the comapany data
        $comSqlQuery = "SELECT COUNT(DISTINCT symbol) FROM portfolio WHERE userid = ?";
        $comResult = $db->prepare($comSqlQuery);
        $comResult->execute([$userID]);
        $totalCom = $comResult->fetchColumn();

        //echo "<p> total number of shares = $totalCom </p>"; debug echo -june

        //get the total shared owned shared by the userID
        $sumStockQueryString = "SELECT SUM(amount) FROM portfolio WHERE userId = ?";
        $sumResult = $db->prepare($sumStockQueryString);
        $sumResult->execute([$userID]);
        $totalSumResult = $sumResult->fetchColumn();

        //echo "<p> summation of the total shares:  $totalSumResult</p>";   debug echo -june (User)

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
// this method will be userd for the company.php to get the data about the company specific. - June (*Symbol)
public static function getCompanyDetails($symbol){
    try {
        $db = DatabaseHelper::createConnection([DBCONNSTRING, DBUSER, DBPASS]);
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
 
//history class
 class HistoryDB {
    private $pdo;
    private static $baseSQL = "SELECT symbol, date, open, close FROM history ";
 
    public function __construct($pdo) { 
        $this->pdo = $pdo; 
    }
 
    //returns history info for a specific sample country
    public function getAllForSymbolAsc($symbol) {
       $sql = self::$baseSQL . " WHERE symbol = ? ORDER BY date ASC";
       $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($symbol));
       return $statement->fetchAll();
    }
 }
 
 //portfolo Class
 class PortfolioDB {
    private $pdo;
    private static $baseSQL = "SELECT p.userId, p.symbol, p.amount, s.name, s.sector 
    FROM portfolio p JOIN companies s ON p.symbol = s.symbol";
 
    public function __construct($pdo) { 
        $this->pdo = $pdo; 
    }
 
    //returns all portrfolios for a specific sample customer
    public function getAllForUser($userId) {
       $sql = self::$baseSQL . " WHERE p.userId = ? ORDER BY p.symbol";
       $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($userId));
       return $statement->fetchAll();
    }
 }
?>