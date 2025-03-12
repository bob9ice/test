<?php
    require '../../db_connection.php';

    try {
        $conn = getConnection();
        
        $id = $_GET['mlHerbName'];
        
        $sql = "SELECT * FROM ml_details WHERE mlHerbName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        
        $herbalDetail = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($herbalDetail) {
            echo json_encode(array($herbalDetail));
        } else {
            echo json_encode(array("message" => "No record found"));
        }
        
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    } finally {
        // Close the connection
        $conn = null;
    }
?>