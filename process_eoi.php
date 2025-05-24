<?php
    // Begin the session
    session_start();

    // Connect to the database
    require_once 'settings.php';

    // Function to sanitise the input
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
            $page_title = "Processing EOI"; // Set the page title
            include 'header.inc'; // Include the header file
        ?>
        <?php include 'nav.inc' ?> <!--Include Navigation Bar-->
    </header>

    <!-- Begin the main content of the web page -->
    <main>
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
                    $job_ref_number = isset($_POST["job_ref_number"]) ? sanitise_input($_POST["job_ref_number"]) : "";
                    if ($job_ref_number == "") 
                    {
                        $errors[] = "Please select the job you are applying for.";
                    }
                    $first_name = isset($_POST["given_name"]) ? sanitise_input($_POST["given_name"]) : "";
                    // Check for empty strings to give better error messages for users
                    if ($first_name == "") 
                    {
                        $errors[] = "Please enter your first name.";
                    } 
                    elseif (!preg_match("/^[a-zA-Z]{1,20}$/", $first_name)) 
                    {
                        $errors[] = "Please only use characters for your first name and ensure it is less than 20 characters.";
                    }
                    $last_name = isset($_POST["family_name"]) ? sanitise_input($_POST["family_name"]) : "";
                    if ($last_name == "") 
                    {
                        $errors[] = "Please enter your last name.";
                    } 
                    elseif (!preg_match("/^[a-zA-Z]{1,20}$/", $last_name)) 
                    {
                        $errors[] = "Please only use characters for your last name and ensure it is less than 20 characters.";
                    }
                    $dob = isset($_POST["dob"]) ? sanitise_input($_POST["dob"]) : "";
                    if ($dob == "") 
                    {
                        $errors[] = "Please enter your date of birth.";
                    }
                    $gender = isset($_POST["gender"]) ? sanitise_input($_POST["gender"]) : "";
                    if ($gender == "") 
                    {
                        $errors[] = "Please select your gender.";
                    }

                    // Address
                    $address = isset($_POST["address"]) ? sanitise_input($_POST["address"]) : "";
                    if ($address == "") 
                    {
                        $errors[] = "Please enter your address.";
                    } 
                    elseif (!preg_match("/^[\da-zA-Z\/ ]{1,40}$/", $address)) 
                    {
                        $errors[] = "Address must be less than 40 characters. Only characters, numbers and slashes(/) are allowed";
                    }
                    $suburb = isset($_POST["suburb"]) ? sanitise_input($_POST["suburb"]) : "";
                    if ($suburb == "") 
                    {
                        $errors[] = "Please enter your suburb.";
                    } 
                    elseif (!preg_match("/^[a-zA-Z ]{1,40}$/", $suburb)) 
                    {
                        $errors[] = "Please only use characters for your suburb and ensure it is less than 40 characters.";
                    }
                    $postcode = isset($_POST["postcode"]) ? sanitise_input($_POST["postcode"]) : "";
                    if ($postcode == "") 
                    {
                        $errors[] = "Please enter your postcode.";
                    } 
                    elseif (!preg_match("/^\d{4}$/", $postcode)) 
                    {
                        $errors[] = "Please ensure your postcode is 4 digits.";
                    }
                    $state = isset($_POST["state"]) ? sanitise_input($_POST["state"]) : "";
                    if ($state == "") 
                    {
                        $errors[] = "Please select your state.";
                    }

                    // Contact Details
                    $phone_number = isset($_POST["phone_number"]) ? sanitise_input($_POST["phone_number"]) : "";
                    if ($phone_number == "") 
                    {
                        $errors[] = "Please enter your phone number.";
                    } 
                    elseif (!preg_match("/^[0-9 ]{8,12}$/", $phone_number)) 
                    {
                        $errors[] = "Please ensure your phone number is between 8 and 12 digits.";
                    }
                    $email = isset($_POST["email"]) ? sanitise_input($_POST["email"]) : "";
                    if ($email == "") 
                    {
                        $errors[] = "Please enter your email address.";
                    } 
                    //([^@\s]+) Make sure there are one or more characters that are not whitespace or the @ symbol
                    //(@) Make sure the @ symbol comes next
                    //([^@\s]+) Make sure there is at least one character that is not whitespace or @ symbol
                    //(\.) Make sure the dot is next
                    //([^@\s]+) Make sure there is at least one character that is not whitespace or @ symbol
                    elseif (!preg_match("/^[^@\s]+@[^@\s]+\.[^@\s]+$/", $email)) 
                    {
                        $errors[] = "Please ensure your email address is the correct format (email@domain.com).";
                    }

                    // Skills
                    $skills = isset($_POST['skills']) ? implode(", ", array_map('sanitise_input', $_POST["skills"])) : "";
                    if ($skills == "") 
                    {
                        $errors[] = "Please select at least one skill";
                    }
                    $other_skills = isset($_POST["other_skills"]) ? sanitise_input($_POST["other_skills"]) : "";

                    // Check for repeated applications using the same email address
                    $query = "SELECT * FROM eoi WHERE email_address = '$email' AND job_ref_number = '$job_ref_number'";
                    $result = mysqli_query($db_conn, $query);
                    if(mysqli_num_rows($result) > 0)
                    {
                        $errors[] = "An application for this position with this email address already exists!";
                    }
                    
                    // Show errors if there are any 
                    if (!empty($errors))
                    {
                        // Save the data that the user has entered in the form in a session variable
                        $_SESSION['form_data'] = $_POST;

                        // Display the errors from the form to the user
                        echo"<h2 class='process_eoi_text'>The following errors were found in your submission:</h2>";
                        // Iterate through each error
                        echo"<ul class='process_eoi_text' id='process_error_list'>";
                        foreach ($errors as $error)
                        {
                            echo"<li>" . htmlspecialchars($error) . "</li>";
                        }
                        echo"</ul>";
                        echo"<p class='process_eoi_text'><strong>Please fix your errors and <a href='apply.php'>resubmit.</a></strong></p>";
                    }
                    else
                    {
                        // Submit the EOI to the database
                        // Check if the eoi table exists (original logic)
                        $check_table_query = "SHOW TABLES LIKE 'eoi'";
                        $check_table_result = mysqli_query($db_conn, $check_table_query);
                        if (mysqli_num_rows($check_table_result) == 0)
                        {
                            // The table doesnt exist so we need to create it (original logic)
                            $create_table = "CREATE TABLE eoi (
                                            eoi_number INT AUTO_INCREMENT PRIMARY KEY,
                                            job_ref_number VARCHAR(5),
                                            first_name VARCHAR(20),
                                            last_name VARCHAR(20),
                                            dob DATE,
                                            gender VARCHAR(20),
                                            address VARCHAR(40),
                                            suburb VARCHAR(40),
                                            state VARCHAR(3),
                                            postcode VARCHAR(4),
                                            email_address VARCHAR(100),
                                            phone_number VARCHAR(12),
                                            skills VARCHAR(100),
                                            other_skills VARCHAR(500),
                                            status VARCHAR(7)
                                            )";
                            // Create the table and print an error message if its not created (original logic)
                            if (!mysqli_query($db_conn, $create_table))
                            {
                                echo"<p class='process_eoi_text'>Error creating eoi table: " . mysqli_error($db_conn) . "</p>";
                                exit();
                            }    
                        }

                        // Insert the data into the eoi table
                        $status = "NEW";
                        // Variables $job_ref_number, $first_name, etc., are already sanitized by your function
                        $insert_eoi_sql = "INSERT INTO eoi (
                                        job_ref_number, first_name, last_name, dob, gender,
                                        address, suburb, state, postcode, phone_number, email_address,
                                        skills, other_skills, status
                                    ) VALUES (
                                        '$job_ref_number', '$first_name', '$last_name', '$dob', '$gender',
                                        '$address', '$suburb', '$state', '$postcode', '$phone_number', '$email',
                                        '$skills', '$other_skills', '$status'
                                    )
                                ";

                        if (mysqli_query($db_conn, $insert_eoi_sql)) {
                            // Get the auto-incremented ID from the EOI insert IMMEDIATELY
                            $eoi_number = mysqli_insert_id($db_conn); 

                            // Display EOI success message
                            echo "<div class=\"profile_container\">";
                            echo "<h2 class='process_eoi_text'><br>Thank you for your application, " . htmlspecialchars($first_name) . ".</h2>";
                            echo "<p class='process_eoi_text'>Your Reference number for your application is: <strong>" . htmlspecialchars($eoi_number) . "</strong>";
                            echo "<br><br>We will get back to you soon.</p>";
                            echo "<button class=\"buttons\" onclick=\"window.location.href='index.php'\">Continue Browsing</button>";
                            echo "</div>";
                            unset($_SESSION['form_data']); // Clear form data on success

                        } else {
                            echo "<p class='process_eoi_text'>Error submitting EOI application: " . mysqli_error($db_conn) . "</p>";
                        }
                    }

                    // Close the DB connection
                    mysqli_close($db_conn);
                }
                else
                {
                    echo"<p class='process_eoi_text'>Unable to connect to the database</p>";
                }
            }
            // Force the user back to the form if they reached the page without submitting the form
            else header ("Location:apply.php"); 
        ?>
        
    </main>
        
    <?php include 'footer.inc' ?> <!--Include Footer-->

</body>
</html>