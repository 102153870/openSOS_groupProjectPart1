<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: manager_login.php");
    exit();
}

// Get username from session
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard - OpenSOS</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1>Manager Dashboard</h1>
        <p>Welcome to the manager dashboard  <?php echo htmlspecialchars($username) ?>!</p>;

</body>
</html>