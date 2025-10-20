<?php
//include
//require_once('includes/config.inc.php');
//updating line 3 with absolute pathing cause why not - June
require_once __DIR__ . '/includes/config.inc.php';
//refrence: https://stackoverflow.com/questions/32537477/how-to-use-dir;
// array for table display
// **STILL NEED TO USE $_GET TO GET ref VALUE**
$endpoints = [
    'api/companies.php'          => 'Returns all the companies/stocks',
    'api/companies.php?ref=ads'  => 'Return just a specific company/stock',
    'api/portfolio.php?ref=8'    => 'Returns all the portfolios for a specific sample customer',
    'api/history.php?ref=AAPL'    => 'Returns the history information for a specific sample company'
];
?>

<!DOCTYPE html>
<html lang=en>
<head>
    <title>Portfolio Project - APIs</title>
    <meta charset=utf-8>
    <link rel="stylesheet" href="assets/globalStyle.css"> 
     <link rel="stylesheet" href="assets/apiStyle.css">
</head>
<body >

<header>
    <h1>APIs</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="apis.php">APIs</a>
    </nav>
</header>

<!---adding inline styling for flex for now later we can get crazy inside CSS aswell -June -->
<main style="display: flex;">
<?php
// URL and Description formatted as Table for Now -- Will change or adjust border colours/layout
echo "<table border='1' cellpadding='6' cellspacing='0'>";
echo "<tr><th>URL</th><th>Description</th></tr>";
// i am sorry man i have an idea to showcase the json in a cool way. I think its cool lets see how it goes. -June

//Loops array output - defined at start of page
foreach ($endpoints as $key => $value) {
    echo "<tr>";
    echo "<td><a href='" . $key . "'>" . $key . "</a></td>";
    echo "<td>" . $value . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
</main>
<footer>
    <p>© <?php echo date("Y"); ?> COMP 3512 Assignment #1 | Mount Royal University</p>
</footer>
</body>
</html>