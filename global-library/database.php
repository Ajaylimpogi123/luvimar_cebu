<?php
require_once 'config.php';

date_default_timezone_set("Asia/Manila");
$today_date1 = date("Y-m-d H:i:s");
$today_date2 = date("Y-m-d");
$today_date3 = date("M d, Y");

// database connection config
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'db_luvimar_cebu';



try {
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$link  = mysqli_connect("$dbHost", "$dbUser", "$dbPass", "$dbName");
