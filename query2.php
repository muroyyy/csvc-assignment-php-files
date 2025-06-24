<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selection'])) {
    $selection = $_POST['selection'];

    // Map query IDs to SQL statements
    $queries = [
        'Q1' => "SELECT country, mobile_phones FROM countrydata WHERE mobile_phones IS NOT NULL",
        'Q2' => "SELECT country, population FROM countrydata WHERE population IS NOT NULL",
        'Q3' => "SELECT country, life_expectancy FROM countrydata WHERE life_expectancy IS NOT NULL",
        'Q4' => "SELECT country, gdp FROM countrydata WHERE gdp IS NOT NULL",
        'Q5' => "SELECT country, mortality_rate FROM countrydata WHERE mortality_rate IS NOT NULL"
    ];

    if (!array_key_exists($selection, $queries)) {
        die("Invalid selection.");
    }

    try {
        $stmt = $pdo->query($queries[$selection]);
        $results = $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

} else {
    die("No query selection received.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Query Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Query Results</h1>
    <p><a href="query.php">← Back to Query Page</a></p>

    <?php if (!empty($results)): ?>
        <table border="1">
            <tr>
                <?php foreach (array_keys($results[0]) as $col): ?>
                    <th><?php echo htmlspecialchars($col); ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($results as $row): ?>
                <tr>
                    <?php foreach ($row as $value): ?>
                        <td><?php echo htmlspecialchars($value); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No data found for your selection.</p>
    <?php endif; ?>
</body>
</html>
