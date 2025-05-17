<?php //Profile Page
// This page displays the user's profile information and allows them to log out.
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
        <h2>Your Profile Information</h2> <br>
        <?php //Display the user's profile information
        $query = "SELECT * FROM eoi WHERE email_address = '" . mysqli_real_escape_string($db_conn, $_SESSION['email']) . "'";
        $result = mysqli_query($db_conn, $query);
        if ($result && mysqli_num_rows($result) > 0) { //Check if there are results in the eoi table
            echo "<table class=\"team_intro\"><tr><th>Name</th><th>DOB</th><th>Gender</th>
            <th>Address</th><th>Suburb</th><th>State</th><th>Postcode</th><th>Email Address</th><th>Phone Number</th>
            </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
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
            }
            echo "</table>";
        } else {
            echo "<p>No records found! Apply now to see your profile info!</p>";
        }
        ?>

        <br><br><br>
        <h2>Your Application</h2> <br>
        <?php //Display the user's application information
        $query = "SELECT * FROM eoi WHERE email_address = '" . mysqli_real_escape_string($db_conn, $_SESSION['email']) . "'";
        $result = mysqli_query($db_conn, $query);
        if ($result && mysqli_num_rows($result) > 0) { //Check if there are results in the eoi table
            echo "<table class=\"team_intro\"><tr><th>Skills</th><th>Other Skills</th></tr>
            </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['skills']}</td>
                    <td>{$row['other_skills']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No records found! Apply now!</p>";
        }
        ?>

        <br><br><br>

        <!-- Logout button -->
        <form method="post">
            <button type="submit" name="logout" class="buttons">Logout</button> 
        </form>

    </div>
</body>