<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: manager_login.php");
    exit();
}

// Get username from session
$username = $_SESSION['username'];

//Check if logout button is pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();         // Unset all session variables
    session_destroy();       // Destroy the session
    header('Location: index.php'); // Redirect to login or another page
    exit;
}

//SECURITY: Check if user is a manager, if not redirect to index.php
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'manager') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard - OpenSOS</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php 
    $page_title = "Dashboard";
    include 'header.inc'; ?>

        <p>Welcome to the manager dashboard  <?php echo htmlspecialchars($username) ?>!</p>
        <form method="post">
            <button type="submit" name="logout" class="buttons">Logout</button> <!-- Logout button -->
        </form>
</body>
</html>