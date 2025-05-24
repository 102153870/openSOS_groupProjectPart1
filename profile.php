<!--Profile.php page to display user's information and update their details/autofill-->
<?php 
session_start(); 

include 'settings.php'; //Database connection settings
$page_title = "Profile";
include 'header.inc'; //Include the header file
include 'nav.inc'; //Include the nav file

//Variables and arrays to store profile update messages and form data
$update_errors = []; //There may be more than 1 error, so the variable is an array
$update_success_message = ''; //Message to display after the profile has been updated

//Profile is successfully updated
if (isset($_SESSION['profile_update_success'])) {
    $update_success_message = $_SESSION['profile_update_success'];
    unset($_SESSION['profile_update_success']); //Clear the message after display
}

//Profile cannot be updated due to existing errors while updating
if (isset($_SESSION['profile_update_errors'])) {
    $update_errors = $_SESSION['profile_update_errors']; 
    unset($_SESSION['profile_update_errors']); //Clear the message after display
}

//Similar to apply page, this variable is used to retrieve previously submitted form data from session (form repopulation)
$profile_form_data = isset($_SESSION['profile_form_data']) ? $_SESSION['profile_form_data'] : [];
//If the edit form was submitted with errors, get the old data to refill the form
unset($_SESSION['profile_form_data']);

//Variable to check if the edit form should be open when the page loads
//Note: The edit form should only load if there are errors in the previous edit attempt
$is_edit_mode_active = !empty($update_errors);

//Section to update profile (PROFILE UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    
    $_SESSION['profile_form_data'] = $_POST; 

    //Clean the data (remove white space)
    //Assign relevant variables to the cleaned user input. If the value is empty for the submission, assign in NULL.
    $edit_first_name = trim($_POST['edit_first_name'] ?? '');
    $edit_last_name = trim($_POST['edit_last_name'] ?? '');
    $edit_dob = trim($_POST['edit_dob'] ?? '');
    $edit_gender = trim($_POST['edit_gender'] ?? '');
    $edit_address = trim($_POST['edit_address'] ?? '');
    $edit_suburb = trim($_POST['edit_suburb'] ?? '');
    $edit_state = trim($_POST['edit_state'] ?? '');
    $edit_postcode = trim($_POST['edit_postcode'] ?? '');
    $edit_phone_number = trim($_POST['edit_phone_number'] ?? '');

    //Current user's email (used to find which user to update in the users table)
    $current_user_email_for_update = $_SESSION['email']; //CANNOT BE EDITED FOR SIMPLICITY

    //User input verification for profile information
    if (empty($edit_first_name)) { $update_errors[] = "First name cannot be empty."; }
    elseif (!preg_match("/^[a-zA-Z]{1,20}$/", $edit_first_name)) { $update_errors[] = "First name: 1-20 letters only."; }
    if (empty($edit_last_name)) { $update_errors[] = "Last name cannot be empty."; }
    elseif (!preg_match("/^[a-zA-Z]{1,20}$/", $edit_last_name)) { $update_errors[] = "Last name: 1-20 letters only."; }
    if (!empty($edit_dob) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $edit_dob)) { $update_errors[] = "Date of Birth must be in YYYY-MM-DD format."; }
    if (!empty($edit_postcode) && !preg_match("/^\d{4}$/", $edit_postcode)) { $update_errors[] = "Postcode must be 4 digits."; }
    if (!empty($edit_phone_number) && !preg_match("/^[0-9 ]{8,12}$/", $edit_phone_number)) { $update_errors[] = "Phone number: 8-12 digits and spaces only."; }
    
    //If there are no errors with the profile update, proceed with updating the users table
    if (empty($update_errors)) {
        $sql_update = "UPDATE users SET 
                        first_name = ?, last_name = ?, dob = ?, gender = ?,
                        address = ?, suburb = ?, state = ?, postcode = ?, phone_number = ?
                       WHERE email = ?";
        
        //Using prepared statments for website security
        $stmt_update = $db_conn->prepare($sql_update);
        if ($stmt_update) {
            // If some optional fields are empty, save them as 'null' (nothing) in the users table
            $dob_to_db = !empty($edit_dob) ? $edit_dob : null;
            $gender_to_db = !empty($edit_gender) ? $edit_gender : null;
            $address_to_db = !empty($edit_address) ? $edit_address : null;
            $suburb_to_db = !empty($edit_suburb) ? $edit_suburb : null;
            $state_to_db = !empty($edit_state) ? $edit_state : null;
            $postcode_to_db = !empty($edit_postcode) ? $edit_postcode : null;
            $phone_to_db = !empty($edit_phone_number) ? $edit_phone_number : null;

            //bind the related parameters, putting the actual values into the SQL command
            $stmt_update->bind_param("ssssssssss",
                $edit_first_name, $edit_last_name, $dob_to_db, $gender_to_db,
                $address_to_db, $suburb_to_db, $state_to_db, $postcode_to_db, $phone_to_db,
                $current_user_email_for_update
            );

            //Run the SQL command
            if ($stmt_update->execute()) {
                //If the update was successful, show success message (stored in session variable)
                $_SESSION['profile_update_success'] = "Profile updated successfully!";

                //User data array that stores the autofill information for the apply page (same as login page)
                $_SESSION['user_data'] = array( 
                'first_name' => $edit_first_name,
                'last_name' => $edit_last_name,
                'dob' => $dob_to_db,
                'gender' => $gender_to_db,
                'address' => $address_to_db,
                'suburb' => $suburb_to_db,
                'state' => $state_to_db,
                'postcode' => $postcode_to_db,
                'phone_number' => $phone_to_db,
                'email' => $current_user_email_for_update
                );
                unset($_SESSION['profile_form_data']);  // Clear the temporary form data from session
            } else { //If the data was unable to be updated in the database
                error_log("Profile Update DB Error: " . $stmt_update->error); //Used for debugging
                $update_errors[] = "An error occurred while updating your profile."; //Error message
                $_SESSION['profile_update_errors'] = $update_errors;
            }
            $stmt_update->close();
        } else { //If the SQL data preparation failed 
            error_log("Profile Update Prepare Error: " . $db_conn->error); //Used for debugging
            $update_errors[] = "An error occurred while preparing to update your profile.";
            $_SESSION['profile_update_errors'] = $update_errors;
        }

        //Return to profile page to show updated info or error messages
        header("Location: profile.php"); 
        exit();
    } else { //The edit form had errors
        //Store the errors wanting to be displayed in the error array
        $_SESSION['profile_update_errors'] = $update_errors;
        header("Location: profile.php");
        exit();
    }
}

//Logout button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

//Ensures the user is logged in, if not redirect them to the index page for SECURITY
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

//GETTING THE USER INFO TO DISPLAY ON THE PROFILE
//Check if there is any optional information submitted from the user during registration
if (isset($_SESSION['user_data']) && $_SESSION['user_data']['email'] == $_SESSION['email']) {
    $row_user = $_SESSION['user_data']; //Store and use the submitted information from the session directly
} else { 
    //If the data doesnt exist in the session, get the info from the database
    $current_user_email = $_SESSION['email']; //Find the users record from the users table
    $stmt_user_fetch = $db_conn->prepare("SELECT * FROM users WHERE email = ?");
    $row_user = null;
    if ($stmt_user_fetch) {
        $stmt_user_fetch->bind_param("s", $current_user_email);
        $stmt_user_fetch->execute();
        $result_user_fetch = $stmt_user_fetch->get_result();

        //If the user exists in the table
        if ($result_user_fetch && $result_user_fetch->num_rows > 0) {
            $row_user = $result_user_fetch->fetch_assoc(); // Fetch the user's data as an associative array
            $_SESSION['user_data'] = $row_user; //Store the data in the session so that no need to query again
        }
        $stmt_user_fetch->close();
    } else { //Fetch failed
        echo "<p>Error fetching profile data.</p>"; 
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 2 profile.php page">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML5, Group Project, Profile Page, OpenSOS">
    <meta name="author" content="Rodney Liaw">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
    <title>OpenSOS</title>
</head>

<body>
    <div class="profile_container">
        <br>
        <h2>Your Profile Information</h2> <br>

        <?php
        if (!empty($update_success_message)) {
            //Display Profile Successfully updated message
            echo "<p class='success_message'>" . htmlspecialchars($update_success_message) . "</p>";
        }
        if (!empty($update_errors)) { //Display all error messages in a list
            echo "<div class='error_message'><strong>Please correct the following errors:</strong><ul>";

            // Reset pointer to the beginning of the array
            reset($update_errors);

            // Loop through each error using while loop
            while (($error = current($update_errors)) !== false) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
                next($update_errors); // Move to the next element
            }
            echo "</ul></div>";
        }
?>
        <!-- Checkbox to switch between viewing and editing profile (Toggling)-->
        <!--Edit profile mode will be automatically open if the previous edit had errors (allows the user to see their errors by maintaining input-->
        <input type="checkbox" id="edit_profile_checkbox" class="profile_editor_toggle" <?php if ($is_edit_mode_active) echo 'checked'; ?>>
        <label for="edit_profile_checkbox" class="profile_editor_toggle_label">
            <?php echo $is_edit_mode_active ? 'Cancel Editing / View Profile' : 'Edit Profile'; ?>
        </label>

        <!-- Normal profile info display (non-editable) -->
        <div id="static_profile_info_container">
        <?php
        if ($row_user) { //User info was found as previous php block
            echo "<div class=\"member_interests\">";
            echo "<table>";
                //NOTE: if the value for the variable is empty (null), the table will display N/A
                //It checks if the variable is set or not (empty or not)

                //Show either the first or last name or both (whichever was submitted by the user)
                $firstName_display = isset($row_user['first_name']) && trim($row_user['first_name']) !== '' ? htmlspecialchars(trim($row_user['first_name'])) : null;
                $lastName_display = isset($row_user['last_name']) && trim($row_user['last_name']) !== '' ? htmlspecialchars(trim($row_user['last_name'])) : null;
                $displayName = "<em>N/A</em>"; //Default display if no value is given
                if ($firstName_display && $lastName_display) { $displayName = $firstName_display . " " . $lastName_display; }
                elseif ($firstName_display) { $displayName = $firstName_display; }
                elseif ($lastName_display) { $displayName = $lastName_display; }
                echo "<tr><th>Name:</th><td>" . $displayName . "</td></tr>";

                //Display DOB
                echo "<tr><th>DOB:</th><td>" . (isset($row_user['dob']) && trim($row_user['dob']) !== '' ? htmlspecialchars(trim($row_user['dob'])) : "<em>N/A</em>") . "</td></tr>";
                
                //Display Gender
                echo "<tr><th>Gender:</th><td>" . (isset($row_user['gender']) && trim($row_user['gender']) !== '' ? htmlspecialchars(trim($row_user['gender'])) : "<em>N/A</em>") . "</td></tr>";
                
                //Display Address
                echo "<tr><th>Address:</th><td>" . (isset($row_user['address']) && trim($row_user['address']) !== '' ? htmlspecialchars(trim($row_user['address'])) : "<em>N/A</em>") . "</td></tr>";
                
                //Display Suburb
                echo "<tr><th>Suburb:</th><td>" . (isset($row_user['suburb']) && trim($row_user['suburb']) !== '' ? htmlspecialchars(trim($row_user['suburb'])) : "<em>N/A</em>") . "</td></tr>";
                
                //Display State
                echo "<tr><th>State:</th><td>" . (isset($row_user['state']) && trim($row_user['state']) !== '' ? htmlspecialchars(trim($row_user['state'])) : "<em>N/A</em>") . "</td></tr>";
                
                //Display Postcode
                echo "<tr><th>Postcode:</th><td>" . (isset($row_user['postcode']) && trim($row_user['postcode']) !== '' ? htmlspecialchars(trim($row_user['postcode'])) : "<em>N/A</em>") . "</td></tr>";
                
                //Display Email
                echo "<tr><th>Email Address:</th><td>" . (isset($row_user['email']) && trim($row_user['email']) !== '' ? htmlspecialchars(trim($row_user['email'])) : "<em>N/A</em>") . "</td></tr>";
                
                //Display Phone Number
                echo "<tr><th>Phone Number:</th><td>" . (isset($row_user['phone_number']) && trim($row_user['phone_number']) !== '' ? htmlspecialchars(trim($row_user['phone_number'])) : "<em>N/A</em>") . "</td></tr>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>No profile records found or error fetching data.</p>";
        }
        ?>
        </div>

    <!--Form to edit profile (shows after edit profile checkbox is ticked)-->
        <div id="edit_profile_form_container">
            <form method="post" action="profile.php" class ="login_container">
                <div class="edit_form_field"> <!--Edit First Name-->
                    <label for="edit_first_name">First Name:</label>
                    <input type="text" id="edit_first_name" name="edit_first_name" value="<?php echo htmlspecialchars($profile_form_data['edit_first_name'] ?? $row_user['first_name'] ?? ''); ?>" required maxlength="20">
                </div>
                <div class="edit_form_field"> <!--Edit Last Name-->
                    <label for="edit_last_name">Last Name:</label>
                    <input type="text" id="edit_last_name" name="edit_last_name" value="<?php echo htmlspecialchars($profile_form_data['edit_last_name'] ?? $row_user['last_name'] ?? ''); ?>" required maxlength="20">
                </div>
                <div class="edit_form_field"> <!--Edit DOB-->
                    <label for="edit_dob">DOB:</label>
                    <input type="date" id="edit_dob" name="edit_dob" value="<?php echo htmlspecialchars($profile_form_data['edit_dob'] ?? $row_user['dob'] ?? ''); ?>">
                </div>
                <div class="edit_form_field"> <!--Edit Gender-->
                    <label for="edit_gender">Gender:</label>
                    <select id="edit_gender" name="edit_gender">
                        <option value="" <?php echo empty($profile_form_data['edit_gender'] ?? $row_user['gender'] ?? '') ? 'selected' : ''; ?>>Please Select</option>
                        <option value="Male" <?php echo (($profile_form_data['edit_gender'] ?? $row_user['gender'] ?? '') == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (($profile_form_data['edit_gender'] ?? $row_user['gender'] ?? '') == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo (($profile_form_data['edit_gender'] ?? $row_user['gender'] ?? '') == 'Other') ? 'selected' : ''; ?>>Other</option>
                        <option value="Prefer Not To Say" <?php echo (($profile_form_data['edit_gender'] ?? $row_user['gender'] ?? '') == 'Prefer Not To Say') ? 'selected' : ''; ?>>Prefer Not To Say</option>
                    </select>
                </div>
                 <div class="edit_form_field">  <!--Edit Address-->
                    <label for="edit_address">Address:</label>
                    <input type="text" id="edit_address" name="edit_address" value="<?php echo htmlspecialchars($profile_form_data['edit_address'] ?? $row_user['address'] ?? ''); ?>" maxlength="40">
                </div>
                <div class="edit_form_field">  <!--Edit Suburb-->
                    <label for="edit_suburb">Suburb:</label>
                    <input type="text" id="edit_suburb" name="edit_suburb" value="<?php echo htmlspecialchars($profile_form_data['edit_suburb'] ?? $row_user['suburb'] ?? ''); ?>" maxlength="40">
                </div>
                <div class="edit_form_field">  <!--Edit State-->
                    <label for="edit_state">State:</label>
                     <select id="edit_state" name="edit_state">
                        <option value="" <?php echo empty($profile_form_data['edit_state'] ?? $row_user['state'] ?? '') ? 'selected' : ''; ?>>Please Select</option>
                        <option value="VIC" <?php echo (($profile_form_data['edit_state'] ?? $row_user['state'] ?? '') == 'VIC') ? 'selected' : ''; ?>>VIC</option>
                        <option value="NSW" <?php echo (($profile_form_data['edit_state'] ?? $row_user['state'] ?? '') == 'NSW') ? 'selected' : ''; ?>>NSW</option>
                        <option value="QLD" <?php echo (($profile_form_data['edit_state'] ?? $row_user['state'] ?? '') == 'QLD') ? 'selected' : ''; ?>>QLD</option>
                        <option value="NT" <?php echo (($profile_form_data['edit_state'] ?? $row_user['state'] ?? '') == 'NT') ? 'selected' : ''; ?>>NT</option>
                        <option value="WA" <?php echo (($profile_form_data['edit_state'] ?? $row_user['state'] ?? '') == 'WA') ? 'selected' : ''; ?>>WA</option>
                        <option value="SA" <?php echo (($profile_form_data['edit_state'] ?? $row_user['state'] ?? '') == 'SA') ? 'selected' : ''; ?>>SA</option>
                        <option value="TAS" <?php echo (($profile_form_data['edit_state'] ?? $row_user['state'] ?? '') == 'TAS') ? 'selected' : ''; ?>>TAS</option>
                        <option value="ACT" <?php echo (($profile_form_data['edit_state'] ?? $row_user['state'] ?? '') == 'ACT') ? 'selected' : ''; ?>>ACT</option>
                    </select>
                </div>
                <div class="edit_form_field"><!--Edit Postcode-->
                    <label for="edit_postcode">Postcode:</label>
                    <input type="text" id="edit_postcode" name="edit_postcode" value="<?php echo htmlspecialchars($profile_form_data['edit_postcode'] ?? $row_user['postcode'] ?? ''); ?>" maxlength="4">
                </div>
                 <div class="edit_form_field"><!--Edit Phone Number-->
                    <label for="edit_phone_number">Phone Number:</label>
                    <input type="tel" id="edit_phone_number" name="edit_phone_number" value="<?php echo htmlspecialchars($profile_form_data['edit_phone_number'] ?? $row_user['phone_number'] ?? ''); ?>" maxlength="12">
                </div>
                <div class="edit_form_field"> <!--Edit Email-->
                    <label>Email Address:</label>
                    <?php echo htmlspecialchars($row_user['email'] ?? 'N/A'); ?> (Cannot be changed)    
                    <br><br>
                </div>

            <!--Save changes button-->
                <button type="submit" name="save_profile" class="buttons">Save Changes</button>
            </form>
        </div>

        <br><br><br>

        <!-- SHOW USER APPLICATION(S) -->
        <h2>Your Application(s)</h2> <br>
        <?php
        $current_user_email_application = $_SESSION['email']; //Used to find the users application throught their email
        //Prepared statement to obtain the user's application skills, other skills and the relevant job title based on job ref
        $stmt_application = $db_conn->prepare("
            SELECT eoi.skills, eoi.other_skills, jobs.job_title
            FROM eoi
            JOIN jobs ON eoi.job_ref_number = jobs.reference_code
            WHERE eoi.email_address = ?
        ");

        //Run the query
        if ($stmt_application) {
            $stmt_application->bind_param("s", $current_user_email_application);
            $stmt_application->execute();
            $result_application = $stmt_application->get_result();
            if ($result_application && $result_application->num_rows > 0) { //User application information exists
                while ($row_app = $result_application->fetch_assoc()) {
                    echo "<div class=\"member_interests application_item\">";
                    echo "<table>";
                    echo "<tr><th>Job Position:</th><td>" . (isset($row_app['job_title']) && trim($row_app['job_title']) !== '' ? htmlspecialchars(trim($row_app['job_title'])) : "<em>N/A</em>") . "</td></tr>";
                    echo "<tr><th>Skills:</th><td>" . (isset($row_app['skills']) && trim($row_app['skills']) !== '' ? htmlspecialchars(trim($row_app['skills'])) : "<em>N/A</em>") . "</td></tr>";
                    echo "<tr><th>Other Skills:</th><td>" . (isset($row_app['other_skills']) && trim($row_app['other_skills']) !== '' ? htmlspecialchars(trim($row_app['other_skills'])) : "<em>N/A</em>") . "</td></tr>";
                    echo "</table>";
                    echo "</div>";
                    echo "<br>";
                }
            } else { //User has not yet applied for jobs yet 
                echo "<p>No application records found! Apply now!</p>";
            }
            $stmt_application->close();
        } else {
            echo "<p>Error preparing to fetch applications.</p>";
        }
        ?>

        <br><br><br>

    <!--Logout button-->
        <form method="post" action="profile.php">
            <button type="submit" name="logout" class="buttons">Logout</button> 
        </form>
    </div>
    <?php
        // Close the DB connection
        mysqli_close($db_conn);
    ?>
</body>
</html>