<?php
session_start(); // Start the session

include 'settings.php'; // Include the settings file for database connection
$page_title = "Profile"; // Set the page title
include 'header.inc'; // Include the header file
include 'nav.inc'; // Include the navigation bar

//Check if logout button is pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();         // Unset all session variables
    session_destroy();       // Destroy the session
    header('Location: index.php'); // Redirect to login or another page
    exit;
}

//SECURITY: Check if user is a logged in, if not redirect to index.php
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- The header of the webpage. Contains the meta tags and Title -->
<head>
    <!-- Metadata tags -->
    <link rel="stylesheet" href="styles/style.css">

    <!-- Adds the OpenSOS Icon to title bar -->
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>

<body>
    <div class="profile_container">
        <br>
        <h2>Your Profile Information</h2>
        <br><br><br>
        <h2>Your Application</h2>
        <br><br><br>

        <!-- Logout button -->
        <form method="post">
            <button type="submit" name="logout" class="buttons">Logout</button> 
        </form>

    </div>
</body>