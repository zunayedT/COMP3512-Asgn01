<?php
//include
//require_once('config.inc.php');

// array for table display
// using "ads" and "1" as temp placeholders from e.g in step 6.
$endpoints = [
    'api/companies.php'          => 'Returns all the companies/stocks',
    'api/companies.php?ref=ads'  => 'Return just a specific company/stock',
    'api/portfolio.php?ref=1'    => 'Returns all the portfolios for a specific sample customer',
    'api/history.php?ref=ads'    => 'Returns the history information for a specific sample company'
];
?>

<!DOCTYPE html>
<html lang=en>
<head>
    <title>Portfolio Project - APIs</title>
    <meta charset=utf-8>
    <!--insert style links later -->
</head>
<body >

<header>
    <h1>APIs</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="apis.php">APIs</a>
    </nav>
    <hr>
</header>

<!---adding inline styling for flex for now later we can get crazy inside CSS aswell -June -->
<main style="display: flex;">
<?php
// URL and Description formatted as Table for Now -- Will change or adjust border colours/layout
echo "<table border='1' cellpadding='6' cellspacing='0'>";
echo "<tr><th>URL</th><th>Description</th></tr>";

//Loops array output - defined at start of page
foreach ($endpoints as $key => $value) {
    echo "<tr>";
    echo "<td><a href='" . htmlspecialchars($key) . "'>" . htmlspecialchars($key) . "</a></td>";
    echo "<td>" . htmlspecialchars($value) . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
</main>
</body>
</html>