<?php
session_start();

require_once 'Database.php';

// Check if the selectedGameID is set in localStorage
if (isset($_SESSION['selectedGameID'])) {
    $selectedGameID = $_SESSION['selectedGameID'];

    // Create an instance of your Database class
    $database = new DataBase();
    $database->dbConnect(); // Assuming you have a method to connect to the database

    // Use the updateStockCount method to update the stock count
    if ($database->updateStockCount('Stocks', $selectedGameID)) {
        // Successfully updated the stock count
        echo "Stock count updated successfully.";
    } else {
        // Failed to update the stock count
        echo "Error updating stock count.";
    }

    // Unset the session variable after updating the stock count
    unset($_SESSION['selectedGameID']);
} else {
    // Handle the case when the selectedGameID is not set
    echo "Error: Game ID not selected.";
}
?>
