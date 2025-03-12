<?php
// Include the database connection function
require_once 'db_connection.php';

// Process only POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get the connection
        $conn = getConnection();
        if (!$conn) {
            echo json_encode([
                'status' => 'failure',
                'message' => 'Database connection failed'
            ]);
            exit;
        }
        
        // Get user credentials
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // IMPORTANT: Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT userID, email FROM users WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        // Check if user exists
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $row['userID'];
            $email = $row['email'];
            
            echo json_encode([
                'status' => 'success',
                'userID' => $id,
                'email' => $email
            ]);
        } else {
            echo json_encode([
                'status' => 'failure',
                'message' => 'Invalid username or password'
            ]);
        }
    } catch (PDOException $e) {
        // Log the error but don't expose details to user
        error_log("Login error: " . $e->getMessage());
        
        echo json_encode([
            'status' => 'failure',
            'message' => 'An error occurred during login'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'failure',
        'message' => 'Invalid request method'
    ]);
}
?>