<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['name']) && isset($_POST['age']) && isset($_POST['email']) && isset($_POST['password'])) {
    if ($db->dbConnect()) {
        if ($db->signUp("emailPass", $_POST['name'],$_POST['age'], $_POST['email'], $_POST['password'])) {
            echo "Sign Up Success";
            echo '<script>window.location.replace("login.html");</script>';
        } else echo "Sign up Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required";
?>
