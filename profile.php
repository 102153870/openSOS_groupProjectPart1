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
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 2 profile.php page">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML5, Group Project, Profile Page, OpenSOS">
    <meta name="author" content="Rodney Liaw">
    <link rel="stylesheet" href="styles/style.css">
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
            // Display the user's profile information in a vertical table
            echo "<div class=\"member_interests\">";
            echo "<table>";

            // Name
            echo "<tr>";
            echo "<th>Name:</th>";
            echo "<td>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "</td>";
            echo "</tr>";

            // DOB
            echo "<tr>";
            echo "<th>DOB:</th>";
            echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
            echo "</tr>";

            // Gender
            echo "<tr>";
            echo "<th>Gender:</th>";
            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
            echo "</tr>";

            // Address
            echo "<tr>";
            echo "<th>Address:</th>";
            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
            echo "</tr>";

            // Suburb
            echo "<tr>";
            echo "<th>Suburb:</th>";
            echo "<td>" . htmlspecialchars($row['suburb']) . "</td>";
            echo "</tr>";

            // State
            echo "<tr>";
            echo "<th>State:</th>";
            echo "<td>" . htmlspecialchars($row['state']) . "</td>";
            echo "</tr>";

            // Postcode
            echo "<tr>";
            echo "<th>Postcode:</th>";
            echo "<td>" . htmlspecialchars($row['postcode']) . "</td>";
            echo "</tr>";

            // Email
            echo "<tr>";
            echo "<th>Email Address:</th>";
            echo "<td>" . htmlspecialchars($row['email_address']) . "</td>";
            echo "</tr>";

            // Phone Number
            echo "<tr>";
            echo "<th>Phone Number:</th>";
            echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
            echo "</tr>";

            echo "</table>";
            echo "</div>"; 

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

        //Check and fetch the related skills, other skills and job title for the user's application
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
            while ($row = $result->fetch_assoc()) {
                echo "<div class=\"member_interests\">";
                echo "<table>";

                //Job Position related to applied Job reference code
                echo "<tr>";
                echo "<th>Job Position:</th>";
                echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
                echo "</tr>";

                //Skills checked (checkbox)
                echo "<tr>";
                echo "<th>Skills:</th>";
                echo "<td>" . htmlspecialchars($row['skills']) . "</td>";
                echo "</tr>";

                //Optional Other skills section
                echo "<tr>";
                echo "<th>Other Skills:</th>";

                // Handle potentially empty 'other_skills'
                $other_skills_display = !empty($row['other_skills']) ? htmlspecialchars($row['other_skills']) : "<em>N/A</em>";
                echo "<td>" . $other_skills_display . "</td>";
                echo "</tr>";
                echo "</table>";

                echo "</div>";
            }
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
