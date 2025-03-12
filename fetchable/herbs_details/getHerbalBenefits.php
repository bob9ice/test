<?php

require '../../db_connection.php';

try {
    $conn = getConnection();
    
    $id = intval($_GET['herbId']);
    
    $sql = "SELECT * FROM herbal_benefits WHERE herbId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    
    $benefits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($benefits);
    
} catch (PDOException $e) {
    echo json_encode(array("error" => "Database error: " . $e->getMessage()));
} finally {
    // Close the connection
    $conn = null;
}
?>