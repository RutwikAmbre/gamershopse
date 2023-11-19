<?php
session_start();

require_once 'DataBase.php'; // Include your Database.php file here

// Function to encrypt data using AES-256-CBC encryption
function encryptData($data, $key, $iv) {
    return openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
}

// Function to decrypt data using AES-256-CBC encryption
function decryptData($data, $key, $iv) {
    return openssl_decrypt($data, 'aes-256-cbc', $key, 0, $iv);
}

// Assuming you have a key for encryption
$key = 'CDAE'; // Replace with your encryption key

$db = new DataBase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the payment information from the POST request using names instead of IDs
    $holderName = isset($_POST['holderName']) ? $_POST['holderName'] : '';
    $pinCode = isset($_POST['pinCode']) ? $_POST['pinCode'] : '';
    $holdersNumber = isset($_POST['holdersNumber']) ? $_POST['holdersNumber'] : '';
    $expirationMonth = isset($_POST['months']) ? $_POST['months'] : '';
    $expirationYear = isset($_POST['years']) ? $_POST['years'] : '';
    $cardMethod = isset($_POST['method']) ? $_POST['method'] : '';
        
    // Generate a random initialization vector (IV)
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

    // Store the payment data in a PHP associative array
    $paymentData = [
        'HolderName' => encryptData($holderName, $key, $iv),
        'PinCode' => encryptData($pinCode, $key, $iv),
        'HoldersNumber' => encryptData($holdersNumber, $key, $iv),
        'ExpirationMonth' => encryptData($expirationMonth, $key, $iv),
        'ExpirationYear' => encryptData($expirationYear, $key, $iv),
        'CardMethod' => encryptData($cardMethod, $key, $iv)
    ];
   

    // Store the encrypted payment data in the database table cInfo
    if ($db->dbConnect()) {
        $hName = $paymentData['HolderName'];
        $hNum = $paymentData['HoldersNumber'];
        $pCode = $paymentData['PinCode'];
        $expMonth = $paymentData['ExpirationMonth'];
        $expYear = $paymentData['ExpirationYear'];
        $method = $paymentData['CardMethod'];

        $query = "INSERT INTO cInfo (hName, hNum, pCode, expMonth, expYear, method) 
                  VALUES ('$hName', '$hNum', '$pCode', '$expMonth', '$expYear', '$method')";
        
        if (mysqli_query($db->connect, $query)) {
			echo "<script>
            		alert('Payment information was validated securely! Redirecting to main page');
                    window.location.href = 'index.html'; // Redirect to main page
                    
            </script>";
        } else {
            echo "Error storing payment information.";
        }
    } else {
        echo "Error: Database connection";
    }
} else {
    echo "Invalid request method";
}
?>
