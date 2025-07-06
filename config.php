<?php
require 'vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient; 
use Aws\Exception\AwsException;

$secretName = "msri/db-credentials";
$region = "ap-southeast-1";

$client = new SecretsManagerClient([
    'region' => $region,
    'version' => '2017-10-17'
]);

try {
    $result = $client->getSecretValue([
        'SecretId' => $secretName,
    ]);

    if (isset($result['SecretString'])) {
        $secret = json_decode($result['SecretString'], true);

        $host = $secret['endpoint'];
        $db   = $secret['dbname'];
        $user = $secret['username'];
        $pass = $secret['password'];
    } else {
        throw new Exception("Secret string is empty");
    }

} catch (AwsException $e) {
    die("Unable to retrieve secret: " . $e->getAwsErrorMessage());
}

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
     die("Database connection failed: " . $e->getMessage());
}
?>
