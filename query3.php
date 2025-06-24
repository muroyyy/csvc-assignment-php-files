<?php
require_once 'config.php';

// Example fallback or manual query handler
try {
    $stmt = $pdo->query("SELECT country, population FROM countrydata WHERE population > 1000000");
    $results = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manual Query Output</title>
</head>
<body>
    <h2>Population over 1 million</h2>
    <table border="1">
        <tr><th>Country</th><th>Population</th></tr>
        <?php foreach ($results as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['country']); ?></td>
            <td><?php echo htmlspecialchars($row['population']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
