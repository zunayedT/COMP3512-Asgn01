<?php
//Portfolio API Page

//include file **THESE DO NOT WORK because file name is not working properly**
require_once __DIR__ . '/../includes/config.inc.php';
require_once __DIR__ . '/../includes/db-classes.inc.php';
//adding absolute pathing here aswell;


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
    $conn = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));
    $gateway = new PortfolioDB($conn);
 
    if ( isCorrectQueryStringInfo("ref") )
       $rows = $gateway->getAllForUser($_GET["ref"]);
    else
       $rows = array();
 
    echo json_encode($rows, JSON_NUMERIC_CHECK);
 } catch (Exception $e) { 
     die($e->getMessage()); 
}
