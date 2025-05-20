<?php
session_start(); // Start fresh session
require_once 'settings.php'; // Ensure this file correctly initializes $db_conn

include 'settings.php'; // Include the settings file for database connection
$page_title = "Enhancements"; // Set the page title
include 'header.inc'; // Include the header file
include 'nav.inc'; // Include the navigation bar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 2 enhancements.php Page">
    <meta name="keywords" content="HTML5, Enhancements, Group Project, Job Application">
    <meta name="author" content="All members of OpenSOS">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhancements</title>

    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>
<body>

    <main>

        <!-- Enhancement 1 -->
        <div class="job_description_box">
            <section class="job_description_left">
                <h1 class="job_title">Job Sorting for Manager</h1>
                <p>We implemented a lockout mechanism that limits login attempts to enhance security. If too many failed attempts are made, the system prevents further login attempts for a specified duration.</p><br>
                <a href="enhancement1_example.html" class="buttons_description_box">View Enhancement</a>
            </section>
            <section class="job_description_right">
                <img src="images/tab_icon.png" alt="Lockout System">
            </section>
        </div>

        <!-- Enhancement 2 -->
        <div class="job_description_box">
            <section class="job_description_left">
                <h1 class="job_title">Manager Registration Page</h1>
                <p>We added a logging feature to track user activity for improved monitoring and troubleshooting.</p><br>
                <a href="enhancement2_example.html" class="buttons_description_box">View Enhancement</a>
            </section>
            <section class="job_description_right">
                <img src="images/activity_log.png" alt="User Activity Logging">
            </section>
        </div>

        <!-- Enhancement 3 -->
        <div class="job_description_box">
            <section class="job_description_left">
                <h1 class="job_title">Access control to manage.php</h1>
                <p>The password reset flow has been streamlined with email verification and security questions.</p><br>
                <a href="enhancement3_example.html" class="buttons_description_box">View Enhancement</a>
            </section>
            <section class="job_description_right">
                <img src="images/password_reset.png" alt="Password Reset Flow">
            </section>
        </div>

        <!-- Enhancement 4 -->
        <div class="job_description_box">
            <section class="job_description_left">
                <h1 class="job_title">Improved Password Reset Flow</h1>
                <p>The password reset flow has been streamlined with email verification and security questions.</p><br>
                <a href="enhancement3_example.html" class="buttons_description_box">View Enhancement</a>
            </section>
            <section class="job_description_right">
                <img src="images/password_reset.png" alt="Password Reset Flow">
            </section>
        </div>

    </main>

    <footer>
        <!-- Replace with your actual footer.inc content -->
        <p>&copy; 2025 OpenSOS. All rights reserved.</p>
    </footer>
</body>
</html>
