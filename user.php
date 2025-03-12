<?php

require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['userID'])) {

    $conn = getConnection();
    $userID = intval($_GET['userID']);

    $stmt = $conn->prepare("SELECT userID, name, username, password, userProfile FROM users WHERE userID = :userID");
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $baseUrl = "http://192.168.100.15/herbafilApi/"; 
        $imageUrl = $baseUrl . $user['userProfile'];

        echo json_encode([
            'userID' => $user['userID'],
            'username' => $user['username'],
            'name' => $user['name'],
            'password' => $user['password'],
            'userProfile' => $imageUrl
        ]);
    } else {
        echo json_encode([
            'error' => true,
            'message' => 'User not found'
        ]);
    }
} else {
    echo json_encode([
        'error' => true,
        'message' => 'Invalid request'
    ]);
}
?>
