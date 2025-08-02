<?php
// Load AWS SDK
require 'aws-autoloader.php';

use Aws\Ssm\SsmClient;

error_log('Retrieving database settings from Parameter Store');

// Step 1: Get the instance region
$ch = curl_init();

// Get a valid TOKEN
$headers = array(
    'X-aws-ec2-metadata-token-ttl-seconds: 21600'
);
$url = "http://169.254.169.254/latest/api/token";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_URL, $url);
$token = curl_exec($ch);

// Get availability zone
$headers = array(
    'X-aws-ec2-metadata-token: '.$token
);
$url = "http://169.254.169.254/latest/meta-data/placement/availability-zone";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
$az = curl_exec($ch);
curl_close($ch);

// Convert AZ to region (remove last letter)
$region = substr($az, 0, -1);

try {
    // Step 2: Create SSM client
    $ssmClient = new SsmClient([
        'version' => 'latest',
        'region'  => $region
    ]);

    // Step 3: Get parameter value from Parameter Store
    $param = $ssmClient->getParameter([
        'Name' => '/msri/db-credentials',
        'WithDecryption' => true
    ]);

    // Step 4: Decode the JSON value
    $result = json_decode($param['Parameter']['Value'], true);

    // Assign variables
    $ep = $result['endpoint'];
    $db = $result['db_name'];
    $un = $result['username'];
    $pw = $result['password'];

} catch (Exception $e) {
    $ep = '';
    $db = '';
    $un = '';
    $pw = '';
    error_log('Error retrieving database settings: ' . $e->getMessage());
}

error_log('Settings are: ' . $ep . " / " . $db . " / " . $un . " / " . $pw);
// echo "Check your Database settings";
?>
