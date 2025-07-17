<?php
require_once __DIR__ . '/vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

if (!class_exists('Aws\SecretsManager\SecretsManagerClient')) {
    die("❌ SecretsManagerClient class failed to load. Check autoload or composer.");
}

$secretName = "msri/db-credentials";
$region = "ap-southeast-1";

try {
    // Explicitly create client with credential fallback (IAM role will be used if no access keys provided)
    $client = new SecretsManagerClient([
        'region' => $region,
        'version' => 'latest',
        'credentials' => false, // use IAM role from EC2 instance metadata
    ]);

    $result = $client->getSecretValue([
        'SecretId' => $secretName,
    ]);

    if (isset($result['SecretString'])) {
        $secret = json_decode($result['SecretString'], true);

        if (!is_array($secret)) {
            throw new Exception("SecretString is not valid JSON.");
        }

        $host = $secret['endpoint'] ?? '';
        $db   = $secret['dbname'] ?? '';
        $user = $secret['username'] ?? '';
        $pass = $secret['password'] ?? '';

        // Validate required fields
        if (!$host || !$db || !$user || !$pass) {
            die("❌ Missing required DB credentials in secret.");
        }

    } else {
        throw new Exception("Secret string is empty.");
    }

} catch (AwsException $e) {
    die("❌ AWS SecretsManager error: " . $e->getAwsErrorMessage());
} catch (Exception $e) {
    die("❌ General error: " . $e->getMessage());
}

// Proceed to DB connection
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>
