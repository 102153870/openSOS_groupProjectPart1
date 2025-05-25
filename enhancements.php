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
                <p>Gave manager accounts the ability to sort applications by:</p>
                <ol>
                    <li>EOI Number</li>
                    <li>User First Name</li>
                    <li>User Last Name</li>
                    <li>Job Status</li>
                </ol>
                <br>
                <a href="manage.php" class="buttons_description_box">View Enhancement</a>
            </section>
            <section class="job_description_right">
                <img src="images/enhancement1.png" alt="Sorting options and table output in the manage page" class="enhancement_image">
            </section>
        </div>

        <!-- Enhancement 2 -->
        <div class="job_description_box">
            <section class="job_description_left">
                <h1 class="job_title">Manager Registration Page</h1>
                <p>Users can register for the manager role through this page. In conjunction to signing up with an email, username, and confirmed password, they must provide also the company password, so that access is exclusive to the approprite users.</p><br>
                <a href="register_manager.php" class="buttons_description_box">View Enhancement</a>
            </section>
            <section class="job_description_right">
                <img src="images/enhancement2.png" alt="Admin Registration Page with email, username, password, retype password, and company password fields" class = "enhancement_image">
            </section>
        </div>

        <!-- Enhancement 3 -->
        <div class="job_description_box">
            <section class="job_description_left">
                <h1 class="job_title">Access control to manage.php</h1>
                <p>Access to manage.php is restricted with a login page. Managers must use their credentials to log in. Their password is also hashed. Please note that you have to logout to see the login page.</p><br>
                <a href="login.php" class="buttons_description_box">View Enhancement</a>
            </section>
            <section class="job_description_right">
                <img src="images/enhancement3.png" alt='The login screen, showing "Login here!" text' class = "enhancement_image">
            </section>
        </div>

        <!-- Enhancement 4 -->
        <div class="job_description_box">
            <section class="job_description_left">
                <h1 class="job_title">Disable Login for Number of Login Attempts</h1>
                <p>Users are given a limited amount of login attempts. Users can be locked out for certain time periods after providing incorrect details. This increases the site's security. Please note that you have to logout to see the login page.</p><br>
                <a href="login.php" class="buttons_description_box">View Enhancement</a>
            </section>
            <section class="job_description_right">
                <img src="images/enhancement4.png" alt="Invalid login with 2 attempts remaining in black bars above the login box" class = "enhancement_image">
            </section>
        </div>

        <!-- Enhancement 5 -->
        <div class="job_description_box">
            <section class="job_description_left">
                <h1 class="job_title">More Options for Manager</h1>
                <p>More options for the manager were integrated to make searching and deleting applications easier. (To view enhancement please login to manager account)</p><br>
                <a href="manage.php" class="buttons_description_box">View Enhancement</a>
            </section>
            <section class="job_description_right">
                <img src="images/enhancement5.png" alt="6 Search / Delete Options for Manager" class = "enhancement_image">
            </section>
        </div>

        <!-- Enhancement 6 -->
        <div class="job_description_box">
            <section class="job_description_left">
                <h1 class="job_title">Form pre-filling</h1>
                <p>Two enhancements were made to the apply.php page</p>
                <ol>
                    <li>  If the user is logged in to an account (user level not manager) the form will pre-fill with the optional information associated with their account (if it was entered upon creation)</li>
                    <li>  If errors are detected after the user attempts to submit an EOI the form will be pre-filled with the entered information when the user selects the link to return to the apply page.</li>
                    <li>  If the user did not enter any optional information during the registration, the user can edit their profile information on the profile page, which will update the autofill on the apply page as well. </p>
                </ol>
                <br>
                <a href="apply.php" class="buttons_description_box">View Enhancement</a>
            </section>
            <section class="job_description_right">
                <img src="images/enhancement6.png" alt="Personal and Technical details forms pre-filled out" class = "enhancement_image">
            </section>
        </div>

    </main>

    <?php include 'footer.inc'; // Include the footer file ?>
</body>
</html>
