<?php
// database.php
function getConnection() {
    $host = "sql105.infinityfree.com";
    $dbname = "if0_38500375_herbafil";
    $username = "if0_38500375";
    $password = "8UwTeFyjfcBkK";
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("SET NAMES utf8mb4");
        return $conn;
    } catch(PDOException $e) {
        // Log error but don't expose details to user
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }
}
?>
