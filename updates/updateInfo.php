<?php
header('Content-Type: application/json');

require '../db_connection.php';

try {
    // Create PDO connection using variables from db_connection.php
    $conn = getConnection();
    
    // Get and validate input
    $userID = isset($_POST['userID']) ? intval($_POST['userID']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($userID <= 0 || empty($name) || empty($username) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields: ' . $userID . ' ' . $name . ' ' . $username . ' ' . $password
        ]);
        exit;
    }

    // Update user in database
    $sql = "UPDATE users SET name = ?, username = ?, password = ? WHERE userID = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare statement'
        ]);
        exit;
    }

    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $username);
    $stmt->bindParam(3, $password);
    $stmt->bindParam(4, $userID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            // Fetch updated user data
            $sql = "SELECT userID, name, username, password FROM users WHERE userID = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to prepare statement for fetching user data'
                ]);
                exit;
            }
            
            $stmt->bindParam(1, $userID, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'message' => 'User details updated successfully',
                'user' => $user
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No changes have been made.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update user details'
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
?>