<?php
    require '../../db_connection.php';

    try {
        $conn = getConnection();
        
        $id = intval($_GET['herbId']);
        
        $sql = "SELECT * FROM herbal_steps WHERE herbId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        
        $steps = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Directly output the JSON array, no extra quotes or text
        echo json_encode($steps);
        
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    } finally {
        // Close the connection
        $conn = null;
    }
?>