<?php
// Database connection details
require '../db_connection.php';

// Process only POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        $conn = getConnection();

        // Get JSON input
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Extract data
        $email = $data['email'] ?? '';
        $newPassword = $data['newPassword'] ?? '';

        
        
        // Validate input
        if (empty($email) || empty($newPassword)) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields'
            ]);
            exit;
        }
        
        
        // Update user password in users table
        // Note: Adjust this query according to your actual table structure
        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $newPassword);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        // Check if password was updated
        if ($stmt->rowCount() > 0) {
            
            echo json_encode([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Email not found or password not updated'
            ]);
        }
        
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    // Handle non-POST requests
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode([
        'success' => false,
        'message' => 'Only POST method is allowed'
    ]);
}
?>