<?php
// Database connection parameters
require '../db_connection.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$otp = $data['otp'];

if (isset($otp)) {
    try {
        // Create database connection
        $conn = getConnection();

        // Prepare SQL statement based on whether otpID is provided
        if ($otp !== null) {
            // Check only OTP value
            $sql = "SELECT otpID FROM otp_table WHERE otp = :otp";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':otp', $otp);
        }

        // Execute the statement
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch();

        if ($result) {

            $sql = "DELETE FROM otp_table WHERE otp = :otp";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':otp', $otp);
            $delRes = $stmt->execute();

            if($delRes){
                echo json_encode(array(
                    'success' => true,
                    'message' => 'Verification Complete!'
                ));
            }else{
                echo json_encode(array(
                    'success' => false,
                    'message' => 'There is a problem with the OTP'
                ));
            }
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Failed to register'
            ));
        }

    } catch (PDOException $e) {
        // Log error (in a production environment, use proper logging)
        error_log("Database Error: " . $e->getMessage());
        echo json_encode(array(
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ));
    } finally {
        // Close connection
        $conn = null;
    }
}
?>