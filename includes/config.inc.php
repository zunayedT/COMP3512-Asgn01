<?php
//allows us to define our database connection in one place          
define('DBCONNSTRING', 'sqlite:./data/stocks.db');
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
}
?>