<?php //Profile Page
// This page displays the user's profile information and allows them to log out.
session_start(); // Start the session

include 'settings.php'; // Include the settings file for database connection
$page_title = "Profile"; // Set the page title
include 'header.inc'; // Include the header file
include 'nav.inc'; // Include the navigation bar

// Check if logout button is pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();         // Unset all session variables
    session_destroy();       // Destroy the session
    header('Location: index.php'); // Redirect to login or another page
    exit;
}

// SECURITY: Check if user is logged in, if not redirect to index.php
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadata tags -->
    <link rel="stylesheet" href="styles/style.css">
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 2 profile.php Page">
    <meta name="keywords" content="HTML5, Table, PHP, Group Project, User Profile">
    <meta name="author" content="Rodney Liaw">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Adds the OpenSOS Icon to title bar -->
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>

<body>
    <div class="profile_container">
        <br>
        <h2>Your Profile Information</h2> <br>
        <?php
        // Using prepared statements instead of escaping strings for security
        $email = $_SESSION['email'];

        // Prepare statement for profile information
        $stmt = $db_conn->prepare("SELECT * FROM eoi WHERE email_address = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the query was successful and if any rows were returned
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Display the user's profile information in a table
            echo "<table class=\"team_intro\"> 
                    <tr><th>Name</th><th>DOB</th><th>Gender</th>
                    <th>Address</th><th>Suburb</th><th>State</th><th>Postcode</th><th>Email Address</th><th>Phone Number</th></tr>";
            echo "<tr>
                    <td>{$row['first_name']} {$row['last_name']}</td>
                    <td>{$row['dob']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$row['address']}</td>
                    <td>{$row['suburb']}</td>
                    <td>{$row['state']}</td>
                    <td>{$row['postcode']}</td>
                    <td>{$row['email_address']}</td>
                    <td>{$row['phone_number']}</td>
                </tr>";
            echo "</table>";
        } else {
            echo "<p>No records found! Apply now to see your profile info!</p>";
        }

        $stmt->close();
        ?>

        <br><br><br>
        <h2>Your Application</h2> <br>
        <?php
        // Prepare statement for user's applications
        $email = $_SESSION['email'];

        $stmt = $db_conn->prepare("
            SELECT eoi.skills, eoi.other_skills, jobs.job_title
            FROM eoi
            JOIN jobs ON eoi.job_ref_number = jobs.reference_code
            WHERE eoi.email_address = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            echo "<table class=\"team_intro\">
                    <tr><th>Job Position</th><th>Skills</th><th>Other Skills</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['job_title']}</td>
                        <td>{$row['skills']}</td>
                        <td>{$row['other_skills']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No application records found! Apply now!</p>";
        }

        $stmt->close();
        ?>

        <br><br><br>

        <!-- Logout button -->
        <form method="post">
            <button type="submit" name="logout" class="buttons">Logout</button> 
        </form>
    </div>
</body>
</html>
