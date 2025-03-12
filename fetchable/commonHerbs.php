<?php
    header('Content-Type: application/json');

    // Database credentials
    require '../db_connection.php';
    
    try {
        // Get connection using the db_connection.php function
        $conn = getConnection();
        
        $sql = "SELECT * FROM common_herbs";
        $stmt = $conn->query($sql);
        
        $herbs = array();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $herbs[] = array(
                "herbID" => $row['herbID'],
                "herbName" => $row['herbName'],
                "herbDescrip" => $row['herbDescrip'],
                "imageUrl" => $row['imageUrl']
            );
        }
        
        echo json_encode($herbs);
        
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    } finally {
        // Close the connection
        $conn = null;
    }
?>