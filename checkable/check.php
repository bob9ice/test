<?php

require '../db_connection.php';

try {
    $conn = getConnection();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$user]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode([
                "success" => false,
                "message" => "Username already exists."
            ]);
        } else {
            echo json_encode([
                "success" => true,
                "message" => "Success"
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
        "message" => "Connection failed: " . $e->getMessage()
    ]);
} finally {
    $conn = null;
}
?>