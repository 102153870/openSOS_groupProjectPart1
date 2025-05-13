<?php
    // Begin the session
    session_start();

    // Connect to the database
    require_once 'settings.php';
?>
<!-- Function to sanitise the input -->
<?php
    function sanitise_input ($data)
    {
        // Remove leading and trailing spaces
        $data = trim($data); 
        // Remove backslashes in front of quotes
        $data = stripslashes($data);
        // Converts HTML special characters like < to &lt;
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html lang="en">
<!-- The header of the webpage. Contains the meta tags and Title -->
<head>
    <!-- Metadata tags -->
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 2 process_eoi.php Page">
    <meta name="keywords" content="HTML5, Forms, Group Project, Job Application">
    <meta name="author" content="Mark Richards">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EOI Submitted</title>

    <link rel="stylesheet" href="styles/style.css">
    <!-- Adds the OpenSOS Icon to title bar -->
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>

<body>
    <header>
        <?php
            $page_title = "process_eio"; // Set the page title
            include 'header.inc'; // Include the header file
        ?>
        <?php include 'nav.inc' ?> <!--Include Navigation Bar-->
    </header>

    <!-- Begin the main content of the web page -->
    <main>
        <h1>Processing Expression of Interest</h1>
        <?php
            // Check if the form has been submitted using the button, if not show an error message
            // This also stops directly accessing the page by typing the URL of the PHP file
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                // If the connection is made succesfully validate and santise all input
                if ($db_conn)
                {
                    // Variable to store error messages
                    $errors = [];

                    // Personal Details
                    if (isset($_POST["job_ref_number"])) $job_ref_number = sanitise_input($_POST["job_ref_number"]);
                    if (isset($_POST['given_name'])) 
                    {
                        $first_name = sanitise_input($_POST['given_name']);
                        if ($first_name == "") 
                        {
                            $errors[] = "First name is required.";
                        } 
                        elseif (!preg_match("/^[a-zA-Z]{1,20}$/", $first_name)) 
                        {
                            $errors[] = "Please only use characters for your first name and ensure it is less than 20 characters.";
                        } 
                    }
                    if (isset($_POST['family_name'])) 
                    {
                        $last_name = sanitise_input($_POST['family_name']);
                        if ($last_name == "") 
                        {
                            $errors[] = "Last name is required.";
                        } 
                        elseif (!preg_match("/^[a-zA-Z]{1,20}$/", $last_name)) 
                        {
                            $errors[] = "Please only use characters for your last name and ensure it is less than 20 characters.";
                        } 
                    }
                    // Do we need to santise input when the user doesn't type anything?
                    if (isset($_POST['dob'])) $dob = sanitise_input($_POST['dob']);
                    // Do we need to santise input when the user doesn't type anything?
                    if (isset($_POST['gender'])) $gender = sanitise_input($_POST['gender']); 

                    // Address
                    if (isset($_POST['address'])) $address = sanitise_input($_POST['address']);
                    if (isset($_POST['suburb'])) $suburb = sanitise_input($_POST['suburb']);
                    if (isset($_POST['postcode'])) $postcode = sanitise_input($_POST['postcode']);
                    // Do we need to santise input when the user doesn't type anything?
                    if (isset($_POST['state'])) $state = sanitise_input($_POST['state']);

                    // Contact Details
                    if (isset($_POST['phone'])) $phone = sanitise_input($_POST['phone']);
                    if (isset($_POST['email'])) $email = sanitise_input($_POST['email']);

                    // Skills
                    if (isset($_POST['skills'])) 
                    {
                        $skills = array_map(function($skill) 
                        {
                            // Sanitise each skill input
                            return sanitise_input($skill);
                        }, $_POST['skills']);
                    }
                    if (isset($_POST['other_skills'])) $other_skills = sanitise_input($_POST['other_skills']);

                    

                    // Show errors if there are any 
                    if (!empty($errors))
                    {
                        echo"<h2>The following errors were found in your submission:</h2>";
                        // Iterate through each error
                        foreach ($errors as $error)
                        {
                            echo"<p>" . htmlspecialchars($error) . "</p>";
                        }
                        echo"<p><strong>Please fix your errors and resubmit.</strong></p>";
                    }
                }
            }
            // Force the user back to the form if they reached the page without submitting the form
            else header ("Location:apply.php");

            // SHow the message on the page to the user confirming that there EOI has been submitted
            echo"<br><p>Thank you for your application $first_name.</p>";
            echo"<p>We will get back to you soon.</p>";
            // Get the EOINumber from the database
            echo"<p>Your Reference number for your application is: $eoi_number</p>";
        ?>
        
    </main>
        
    <?php include 'footer.inc' ?> <!--Include Footer-->

</body>
</html>