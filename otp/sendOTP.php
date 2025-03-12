<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


$mail = new PHPMailer(true);

require '../db_connection.php';



try {
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   //Enable SMTP authentication
    $mail->Username = 'herbafil.noreply@gmail.com';                     //SMTP username
    $mail->Password = 'uxaz vzpq ukbn hmzb';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    function simpleNumericOTP($length = 6)
    {
        return sprintf('%0' . $length . 'd', random_int(0, pow(10, $length) - 1));
    }

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $email = $data['email'] ?? '';
    $name = $data['name'] ?? '';


    if (isset($email) && isset($name)) {
        //Recipients
        $mail->setFrom('herbafil.noreply@gmail.com', 'otp_noreply');
        $mail->addAddress($email, $name);     //Add a recipient
        $mail->addReplyTo('herbafil.noreply@gmail.com', 'Information');

        $conn = getConnection();

        $otp = simpleNumericOTP();

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO otp_table (otp) VALUES (:otp)");
        $stmt->bindParam(':otp', $otp);

        $result = $stmt->execute();

        if ($result) {
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Herbafil OTP';
            $mail->Body = 'Your OTP for verification 
            <br> Thank you for using Herbafil.
            Please use the code below to authenticate your e-mail address. <br> <b>' . $otp . '</b>';
            $mail->AltBody = 'Your OTP for verification' . $otp . ' Thank you for using Herbafil.';


            $emailSent = $mail->send();

            if($emailSent){
                echo json_encode(array(
                    'success'=> true,
                    'message'=> 'OTP sent successfully',
                    'otp' => $otp
                ));
            }

            
        }
        else{
            echo json_encode(array(
                'success'=> false,
                'message'=> 'OTP failed to send',
                'otp' => 0
            ));
        }

        
    }

} catch (Exception $e) {
    echo json_encode(array(
        'success'=> false,
        'message'=> "Message could not be sent. Mailer Error: {$mail->ErrorInfo}",
        'otp' => 0
    ));
}

?>