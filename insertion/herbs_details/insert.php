<?php
    require '../../db_connection.php';

    try {
        // Get connection from db_connection.php
        $conn = getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] == 'insertHerbalDetail') {
                $herbName = $_POST['herbName'];
                $herbDescription = $_POST['herbDescription'];
                $herbImage = $_POST['herbImage'];
        
                $sql = "INSERT INTO herbal_details (herbName, herbDescription, herbImage) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$herbName, $herbDescription, $herbImage]);
        
                echo "Herbal Detail record created successfully";
            } elseif ($_POST['action'] == 'insertHerbalBenefits') {
                $herbId = $_POST['herbId'];
                $benefitDescription = $_POST['benefitDescription'];
                $benefitImageUrl = $_POST['benefitImageUrl'];
        
                $sql = "INSERT INTO herbal_benefits (herbId, benefitDescription, benefitImageUrl) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$herbId, $benefitDescription, $benefitImageUrl]);
        
                echo "Herbal Benefits record created successfully";
            } elseif ($_POST['action'] == 'insertHerbalSteps') {
                $herbId = $_POST['herbId'];
                $stepTitle = $_POST['stepTitle'];
                $stepDetails = $_POST['stepDetails'];
        
                $sql = "INSERT INTO herbal_steps (herbId, stepTitle, stepDetails) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$herbId, $stepTitle, $stepDetails]);
        
                echo "Herbal Steps record created successfully";
            }
        }
        
        // Get Herbal Details
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'getHerbalDetails') {
            $sql = "SELECT * FROM herbal_details";
            $stmt = $conn->query($sql);
            $herbalDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($herbalDetails);
        }
        
        // Get Herbal Benefits
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'getHerbalBenefits') {
            $sql = "SELECT * FROM herbal_benefits";
            $stmt = $conn->query($sql);
            $herbalBenefits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($herbalBenefits);
        }
        
        // Get Herbal Steps
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'getHerbalSteps') {
            $sql = "SELECT * FROM herbal_steps";
            $stmt = $conn->query($sql);
            $herbalSteps = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($herbalSteps);
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    } finally {
        // Close the connection
        $conn = null;
    }
?>