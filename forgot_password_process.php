<?php
// forgot_password_process.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// Your database connection code goes here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    // (Replace 'your_table_name' with your actual table name)
    $query = "SELECT email FROM your_table_name WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $result = $stmt->fetch();

    if ($result) {
        // Generate a unique token for password reset
        $resetToken = bin2hex(random_bytes(32));

        // Store the token in the database with a timestamp
        // (Replace 'your_table_name' with your actual table name)
        $query = "UPDATE your_table_name SET reset_token = ?, reset_token_timestamp = NOW() WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$resetToken, $email]);

        // Send the reset token to the user's email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.yourdomain.com'; // Replace with your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_username'; // Replace with your SMTP username
            $mail->Password   = 'your_password'; // Replace with your SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587; // Adjust the port if necessary

            //Recipients
            $mail->setFrom('your_email@example.com', 'Your Name'); // Replace with your email and name
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Hello,<br><br>You requested a password reset. Click the link below to reset your password:<br><br><a href='https://yourwebsite.com/reset_password.php?token=$resetToken'>Reset Password</a>";

            $mail->send();
            echo "Password reset instructions sent to your email.";
        } catch (Exception $e) {
            echo "Error sending email: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found. Please enter a valid email address.";
    }
} else {
    echo "Invalid request.";
}
?>