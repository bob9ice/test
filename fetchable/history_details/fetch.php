<?php
    header('Content-Type: application/json'); // Set the content type to JSON

    require '../../db_connection.php';

    // Get the userID from the query string (e.g., ?userID=1)
    $userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;

    $response = array(); // Initialize response array

    if ($userID <= 0) {
        $response['error'] = "Invalid userID";
        echo json_encode($response);
        exit;
    }

    try {
        // Create a new PDO instance
        $conn = getConnection();

        // SQL query to check if userID exists
        $checkSql = "SELECT COUNT(*) FROM user_history WHERE userID = :userID";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $checkStmt->execute();
        $userExists = $checkStmt->fetchColumn() > 0;

        if (!$userExists) {
            $response['error'] = "userID does not exist";
            echo json_encode($response);
            exit;
        }

        // SQL query to join the tables and fetch history for the specific userID in descending order
        $sql = "SELECT ml_details.mlHerbName, ml_details.mlLimitedDescript, mlHerbImageUrl, user_history.created_at
                FROM ml_details
                INNER JOIN user_history ON ml_details.mlHerbName = user_history.mlHerbName
                WHERE user_history.userID = :userID
                ORDER BY user_history.created_at DESC";

        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            $response['data'] = $results;
        } else {
            $response['message'] = "No history found for this userID";
        }

    } catch (PDOException $e) {
        $response['error'] = "Error: " . $e->getMessage();
    }

    // Close the connection
    $pdo = null;

    // Output the JSON response
    echo json_encode($response);
?>
