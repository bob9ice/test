<?php

require '../../db_connection.php';

try {
    $conn = getConnection();
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data['userID']) && isset($data['mlHerbName'])) {
        $userID = $data['userID'];
        $mlHerbName = $data['mlHerbName'];
        
        $stmt = $conn->prepare("INSERT INTO user_history (userID, mlHerbName) VALUES (?, ?)");
        
        if ($stmt->execute([$userID, $mlHerbName])) {
            echo json_encode(array("message" => "Record inserted successfully"));
        } else {
            echo json_encode(array("error" => "Error inserting record"));
        }
    } else {
        echo json_encode(array("error" => "Invalid input"));
    }
} catch (PDOException $e) {
    echo json_encode(array("error" => "Database error: " . $e->getMessage()));
} finally {
    $conn = null; // Close the connection
}

?>
