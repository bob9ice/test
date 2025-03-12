<?php
require '../../db_connection.php';

try {
    $conn = getConnection();
    
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'insertHerbalDetail':
                $mlHerbName = $_POST['mlHerbName'] ?? '';
                $mlHerbDescription = $_POST['mlHerbDescription'] ?? '';
                $mlHerbImageUrl = $_POST['mlHerbImageUrl'] ?? '';
    
                if ($mlHerbName && $mlHerbDescription && $mlHerbImageUrl) {
                    $sql = "INSERT INTO ml_details (mlHerbName, mlHerbDescription, mlHerbImageUrl) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    
                    if ($stmt->execute([$mlHerbName, $mlHerbDescription, $mlHerbImageUrl])) {
                        echo json_encode(["message" => "Herbal Detail record created successfully"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["error" => "Failed to create record"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Missing required fields"]);
                }
                break;
    
            case 'insertHerbalBenefit':
                $mlHerbName = $_POST['mlHerbName'] ?? '';
                $mlBenefitDescription = $_POST['mlBenefitDescription'] ?? '';
                $mlBenefitImageUrl = $_POST['mlBenefitImageUrl'] ?? '';
    
                if ($mlHerbName && $mlBenefitDescription && $mlBenefitImageUrl) {
                    $sql = "INSERT INTO ml_benefits (mlHerbName, mlBenefitDescription, mlBenefitImageUrl) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    
                    if ($stmt->execute([$mlHerbName, $mlBenefitDescription, $mlBenefitImageUrl])) {
                        echo json_encode(["message" => "Herbal Benefit record created successfully"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["error" => "Failed to create record"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Missing required fields"]);
                }
                break;
    
            case 'insertHerbalStep':
                $mlHerbName = $_POST['mlHerbName'] ?? '';
                $mlStepTitle = $_POST['mlStepTitle'] ?? '';
                $mlStepDetails = $_POST['mlStepDetails'] ?? '';
    
                if ($mlHerbName && $mlStepTitle && $mlStepDetails) {
                    $sql = "INSERT INTO ml_steps (mlHerbName, mlStepTitle, mlStepDetails) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    
                    if ($stmt->execute([$mlHerbName, $mlStepTitle, $mlStepDetails])) {
                        echo json_encode(["message" => "Herbal Step record created successfully"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["error" => "Failed to create record"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Missing required fields"]);
                }
                break;
    
            default:
                http_response_code(400);
                echo json_encode(["error" => "Invalid action"]);
                break;
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'getHerbalDetails':
                if (isset($_GET['mlHerbName'])) {
                    $mlHerbName = $_GET['mlHerbName'];
                    $sql = "SELECT * FROM ml_details WHERE mlHerbName = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$mlHerbName]);
                    
                    $herbalDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode($herbalDetails);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Missing mlHerbName parameter"]);
                }
                break;
                
            case 'getHerbalBenefits':
                if (isset($_GET['mlHerbName'])) {
                    $mlHerbName = $_GET['mlHerbName'];
                    $sql = "SELECT * FROM ml_benefits WHERE mlHerbName = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$mlHerbName]);
                    
                    $herbalBenefits = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode($herbalBenefits);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Missing mlHerbName parameter"]);
                }
                break;
                
            case 'getHerbalSteps':
                if (isset($_GET['mlHerbName'])) {
                    $mlHerbName = $_GET['mlHerbName'];
                    $sql = "SELECT * FROM ml_steps WHERE mlHerbName = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$mlHerbName]);
                    
                    $herbalSteps = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode($herbalSteps);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Missing mlHerbName parameter"]);
                }
                break;
        }
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} finally {
    // Close connection
    $conn = null;
}
?>