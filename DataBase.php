<?php
class DataBase
{
    public $connect;
    public $data;
    private $sql;
    protected $servername = 'fdb1032.awardspace.net'; // AWARDSPACE database host
    protected $username = '4401056_emailpass'; // AWARDSPACE database username
    protected $password = 'Rutwik@123'; // AWARDSPACE database password
    protected $databasename = '4401056_emailpass'; // AWARDSPACE database name
    protected $port = 3306; // AWARDSPACE database port

    // Rest of the class remains unchanged

    public function __construct()
    {
        $this->connect = null;
        $this->data = null;
        $this->sql = null;
    }

    function dbConnect()
    {
        $this->connect = mysqli_connect($this->servername, $this->username, $this->password, $this->databasename);
        return $this->connect;
    }

    function prepareData($data)
    {
        return mysqli_real_escape_string($this->connect, stripslashes(htmlspecialchars($data)));
    }

    function logIn($table, $email, $password)
    {
        $email = $this->prepareData($email);
        $password = $this->prepareData($password);
        $this->sql = "select * from " . $table . " where email = '" . $email . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['email'];
            $dbpassword = $row['password'];
            if ($dbemail == $email && password_verify($password, $dbpassword)) {
                $login = true;
            } else $login = false;
        } else $login = false;

        return $login;
    }

    function signUp($table, $name, $age, $email, $password)
    {
        $name = $this->prepareData($name);
        $age = $this->prepareData($age);
        $email = $this->prepareData($email);
        $password = $this->prepareData($password);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->sql =
            "INSERT INTO " . $table . " (name, age, email, password) VALUES ('" . $name . "','" . $age . "','" . $email . "','" . $password . "')";
        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else return false;
    }

    function profile($table, $email)
    {
        $email = $this->prepareData($email);
        $this->sql = "select name, age, allergies, email from " . $table . " where email = '" . $email . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            return $row;
        } else {
            return false;
        }
    }

    // This method will be used to retrieve the user's profile based on their email
    function forgotPass($table, $email, $newPassword)
    {
        $email = $this->prepareData($email);
        $newPassword = $this->prepareData($newPassword);
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->sql = "update " . $table . " set password = '" . $newPassword . "' where email = '" . $email . "'";
        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else {
            return false;
        }
    }

    function Fooditem($table, $foodId)
    {
        $foodId = $this->prepareData($foodId);
        $this->sql = "select foodName, ingredients, energy, fat, saturates, carbohydrates, sugars, fibre, protein, salt  from " . $table . " where foodId = '" . $foodId . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            return $row;
        } else {
            return false;
        }
    }
}
?>
