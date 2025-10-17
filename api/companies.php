<?php
//Companies API Page

//include file **THESE DO NOT WORK because file name is not working properly**
//yeah we need to use abosolute pathing inorder to make the program more robust. -June 10/12/2025
require_once __DIR__ . '/../includes/config.inc.php';
require_once __DIR__ . '/../includes/db-classes.inc.php';
//refrence stack oveflow: https://stackoverflow.com/questions/32537477/how-to-use-dir;


//browser expects JSON instead of HTML
header('Content-type: application/json');
//states if other domains can use this API
header("Access-Control-Allow-Origin: *");

function isCorrectQueryStringInfo($param) {
    if ( isset($_GET[$param]) && !empty($_GET[$param]) ) {
        return true; 
    } else {
        return false; 
    }
}

try {
    //updating the way we access the database with abosolute pathing and proper sqllite commands - June
    $dbPath = __DIR__ . '/../data/stocks.db';
    $conn = new PDO("sqlite:" . $dbPath);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $gateway = new CompaniesDB($conn);
 
    if ( isCorrectQueryStringInfo("ref") )
       $rows = $gateway->getOneBySymbol($_GET["ref"]);
    else
       $rows = $gateway->getAll();
 
    echo json_encode($rows, JSON_NUMERIC_CHECK);
 } catch (Exception $e) { 
     die($e->getMessage()); 
}
