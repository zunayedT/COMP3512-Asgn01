<?php
require_once __DIR__ . '/includes/config.inc.php';

// check for symbol parameter
if (!isset($_GET['ref']) || empty($_GET['ref'])) {
    die("<p>No company selected. Please go back and choose a portfolio company.</p>");
}

$symbol = $_GET['ref'];
$data = DatabaseHelper::getCompanyDetails($symbol);   
//function to create dynamic HTML For the page. 
function showCompanyData($data) {
    $company = $data['company'];
    $financials = $data['financials'];
    $history = $data['history'];
    $stats = $data['stats'];

    if (!$company) {
        echo "<p>No company found.</p>";
        return;
    }

    echo "<section class='company-info'>";
    echo "<h2>{$company['name']} ({$company['symbol']})</h2>";
    echo "<p><strong>Sector:</strong> {$company['sector']} | <strong>Subindustry:</strong> {$company['subindustry']}</p>";
    echo "<p><strong>Exchange:</strong> {$company['exchange']}</p>";
    echo "<p><strong>Website:</strong> <a href='{$company['website']}' target='_blank'>{$company['website']}</a></p>";
    echo "<p>{$company['description']}</p>";

    // --- financials table ---
    if (!empty($financials)) {
        echo "<h3>Financials</h3>";
        echo "<table class='financial-table'>
                <thead><tr>
                    <th>Year</th><th>Revenue</th><th>Earnings</th><th>Assets</th><th>Liabilities</th>
                </tr></thead><tbody>";

        for ($i = 0; $i < count($financials['years']); $i++) {
            $y = $financials['years'][$i];
            $rev = number_format($financials['revenue'][$i]);
            $earn = number_format($financials['earnings'][$i]);
            $as = number_format($financials['assets'][$i]);
            $liab = number_format($financials['liabilities'][$i]);

            echo "<tr>
                    <td>$y</td><td>$rev</td><td>$earn</td><td>$as</td><td>$liab</td>
                  </tr>";
        }
        echo "</tbody></table>";
    }

    // --- history table ---
    echo "<h3>History (3M)</h3>";
    echo "<table class='history-table'>
            <thead><tr><th>Date</th><th>Volume</th><th>Open</th><th>Close</th><th>High</th><th>Low</th></tr></thead><tbody>";
    foreach ($history as $row) {
        echo "<tr>
                <td>{$row['date']}</td>
                <td>" . number_format($row['volume']) . "</td>
                <td>\${$row['open']}</td>
                <td>\${$row['close']}</td>
                <td>\${$row['high']}</td>
                <td>\${$row['low']}</td>
              </tr>";
    }
    echo "</tbody></table>";

    // --- stats boxes ---
    echo "<div class='stats-boxes'>
            <div class='stat-box'><strong>History High:</strong> \${$stats['maxHigh']}</div>
            <div class='stat-box'><strong>History Low:</strong> \${$stats['minLow']}</div>
            <div class='stat-box'><strong>Total Volume:</strong> {$stats['totalVolume']}</div>
            <div class='stat-box'><strong>Average Volume:</strong> {$stats['avgVolume']}</div>
          </div>";

    echo "</section>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($data['company']['name'] ?? 'Company') ?> - Details</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
  <h1>Portfolio Project</h1>
  <nav>
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="apis.php">APIs</a>
  </nav>
  <hr>
</header>

<main class="company-container">
  <?php showCompanyData($data); ?>
</main>

</body>
</html>
