<?php
require "DataBase.php";
$db = new DataBase();

if (isset($_POST['email']) && isset($_POST['password'])) {
    if ($db->dbConnect()) {
        // Use prepared statements to prevent SQL injection
        $email = $_POST['email'];
        $password = hash('sha256', $_POST['password']); // Hash the password using SHA256

        $stmt = $db->connect->prepare("SELECT * FROM emailPass WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $dbemail = $row['email'];
            $dbpassword = $row['password'];

            // Use hash_equals to compare hashed passwords securely
            if ($dbemail == $email && hash_equals($dbpassword, $password)) {
                session_start();
                $_SESSION['user_email'] = $email;
                header('Location: index.php'); // Assuming your home page is PHP, update it accordingly
                exit;
            } else {
                echo '<script>alert("Email or Password wrong");</script>';
                echo '<script>window.location.replace("login.html");</script>';
            }
        } else {
            echo '<script>alert("Email or Password wrong");</script>';
            echo '<script>window.location.replace("login.html");</script>';
        }

        $stmt->close();
    } else {
        echo "Error: Database connection";
    }
} else {
    echo "All fields are required";
}
?>

