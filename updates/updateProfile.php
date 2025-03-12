<?php
// Set JSON header
header('Content-Type: application/json');

// Get JSON data from request body
$data = json_decode(file_get_contents('php://input'), true);

require_once '../db_connection.php'; // File with getConnection function

try {
    // Get database connection
    $conn = getConnection();
    if (!$conn) {
        echo json_encode([
            'success' => false, 
            'message' => "Database connection failed"
        ]);
        exit();
    }

    $response = array();

    if(isset($data['userId']) && isset($data['userProfile'])) {
        $userId = $data['userId'];
        $base64Image = $data['userProfile'];

        // Decode Base64 to binary
        $imageData = base64_decode($base64Image);
        
        // Check if the base64 was decoded correctly
        if ($imageData === false) {
            echo json_encode([
                'success' => false, 
                'message' => "Invalid image data provided"
            ]);
            exit();
        }
        
        // Create directory if it doesn't exist
        $target_dir = "../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Generate filename
        $target_file = $target_dir . $userId . '_' . time() . '.jpg';
        $db_filename = "uploads/" . $userId . '_' . time() . '.jpg';
        
        // Save decoded image data directly to file
        $result = file_put_contents($target_file, $imageData);
        if ($result !== false) {
            // Update user profile in database using PDO
            $stmt = $conn->prepare("UPDATE users SET userProfile = :profile WHERE userId = :userId");
            $stmt->bindParam(':profile', $db_filename);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Profile image updated successfully.";
                $response['file_path'] = $db_filename;
            } else {
                $response['success'] = false;
                $response['message'] = "Database error: Unable to update profile.";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Sorry, there was an error saving your file.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Missing required data (userId or image).";
    }

    echo json_encode($response);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => "Database error: " . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => "Error: " . $e->getMessage()
    ]);
}
?>