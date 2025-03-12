<?php
header('Content-Type: application/json');

require_once 'db_connection.php'; // Make sure this is the file with getConnection function

try {
    // Get database connection
    $conn = getConnection();
    if (!$conn) {
        echo json_encode([
            "success" => false,
            "message" => "Database connection failed"
        ]);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $email = $_POST['email'];

        // Validate input
        if (empty($name) || empty($user) || empty($pass) || empty($email)) {
            echo json_encode([
                "success" => false,
                "message" => "Name, username, email, and password cannot be empty."
            ]);
            exit();
        }

        // Check if username already exists
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE username = :username");
        $stmt->bindParam(':username', $user);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            echo json_encode([
                "success" => false,
                "message" => "Username already exists."
            ]);
            exit();
        }

        // Insert the new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, username, password) VALUES (:name, :email, :username, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $user);
        $stmt->bindParam(':password', $pass);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Sign-up successful!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error during signup."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid request method."
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>