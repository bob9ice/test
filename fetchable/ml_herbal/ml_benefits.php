<?php

require '../../db_connection.php';

try {
    $conn = getConnection();
    
    $id = $_GET['mlHerbName'];
    
    $sql = "SELECT * FROM ml_benefits WHERE mlHerbName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    
    $benefits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($benefits);
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
} finally {
    // Close the connection
    $conn = null;
}
?>