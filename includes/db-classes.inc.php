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