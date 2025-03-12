<?php

require '../db_connection.php';  

try {
    $conn = getConnection();
    
    $imageUrl = $_POST['imageUrl'];
    $herbName = $_POST['herbName'];
    $herbDescrip = $_POST['herbDescrip'];
    
    $stmt = $conn->prepare("INSERT INTO common_herbs (imageUrl, herbName, herbDescrip) VALUES (?, ?, ?)");
    $stmt->execute([$imageUrl, $herbName, $herbDescrip]);
    
    echo "New record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn = null; // close the connection
}
?>