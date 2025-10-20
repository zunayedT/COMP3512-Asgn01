<?php
// about.php — No database connection required
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Portfolio Project - About</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/globalStyle.css">
    <link rel="stylesheet" href="assets/aboutStyle.css">
</head>
<body>

<header>
    <h1>About</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="apis.php">APIs</a>
    </nav>
</header>

<main>
    <section>
        <h2>About This Project</h2>
        <p>
            This site was developed as part of <strong>Assignment #1</strong> for 
            <em>COMP 3512 – Web II (Server-Side Scripting)</em> at Mount Royal University.  
            It demonstrates a PHP-based web application that connects to a SQLite database
            using <strong>PDO</strong> to display user portfolios and stock market data.
        </p>

        <h2>Home Page Overview (<code>index.php</code>)</h2>
        <p>
            The Home Page is the core of this assignment. It connects to the SQLite database 
            <code>data/stocks.db</code> using PHP PDO and dynamically lists all users 
            from the <code>users</code> table. Each user has a “Portfolio” button 
            that passes their <code>userID</code> to the same page using a GET parameter.
        </p>

        <p>
            When a user’s portfolio button is clicked, the page retrieves their complete 
            investment summary through a helper function 
            <code>DatabaseHelper::getPortfolioData($userID)</code>.  
            This data includes the total number of companies owned, the total shares held, 
            and the total portfolio value. The information is displayed in a 
            structured table through the <code>showPortfolioData()</code> function, 
            which also links each company name and symbol to the 
            <code>company.php</code> page for detailed historical data.
        </p>

        <p>
            The page structure is organized into two main sections using 
            <code>flexbox</code>:
        </p>
        <ul class="tech-list">
            <li><strong>Left panel:</strong> Lists all users and their portfolio links.</li>
            <li><strong>Right panel:</strong> Displays the selected user’s portfolio summary and detailed table.</li>
        </ul>

        <p>
            Inline PHP comments throughout <code>index.php</code> describe the design choices 
            (e.g., why SQLite doesn’t require credentials and how the PDO error mode is handled).  
            This layout allows smooth navigation and sets up a foundation for the upcoming 
            <code>company.php</code> and <code>apis.php</code> pages.
        </p>

        <h2>Technologies Used</h2>
        <ul class="tech-list">
            <li>PHP 8 (with PDO for SQLite database connections)</li>
            <li>HTML5 and CSS3 for structure and styling</li>
            <li>SQLite database (<code>data/stocks.db</code>)</li>
            <li>JSON APIs for data retrieval and testing (<code>apis.php</code>)</li>
            <li>GitHub for version control and collaboration</li>
        </ul>

        <h2>Developers</h2>
        <p>
            Developed by <strong>MD Junayed Talukdar </strong> and <Strong> Tyler Mulvey </strong>, 2025.<br>
            GitHub Repository: 
            <a href="https://github.com/zunayedT/COMP3512-Asgn01" target="_blank">
                CheckOut our code at GitHub!!!
            </a>
        </p>
    </section>
</main>

<footer>
    <p>© <?php echo date("Y"); ?> COMP 3512 Assignment #1 | Mount Royal University</p>
</footer>

</body>
</html>
