<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['email']) && isset($_POST['password'])) {
    if ($db->dbConnect()) {
        if ($db->logIn("userLogin", $_POST['email'], $_POST['password'])) {
            echo "Login Success";
        } else echo "email or Password wrong";
    } else echo "Error: Database connection";
} else echo "All fields are required";
?>