<?php
$host = "shuttle.proxy.rlwy.net";
$port = 47299;
$username = "root";
$password = "aFYqEPKSyNOHSrdnNNEyCMdjWxmxUKyt";
$dbname = "railway";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
