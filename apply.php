<?php
session_start();
require_once 'settings.php'; // Ensure this path is correct

// Get form data from session if it exists
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

// Clear the session data after retrieving it
unset($_SESSION['form_data']);

// Ensure $db_conn is available for the job reference dropdown
if (!$db_conn) {
    // Handle database connection error, perhaps redirect or show a message
    // For now, we'll let it proceed, but the dropdown might be empty or cause errors.
    // error_log("Database connection failed in apply.php");
}

// Fetch user data if logged in, for pre-filling
$user_data = isset($_SESSION['user_data']) ? $_SESSION['user_data'] : [];

?>

<!DOCTYPE html>
<html lang="en">
<!-- The header of the webpage. Contains the meta tags and Title -->
<head>
    <!-- Metadata tags -->
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 2 apply.php Page">
    <meta name="keywords" content="HTML5, Forms, Group Project, Job Application">
    <meta name="author" content="Mark Richards">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application</title>

    <link rel="stylesheet" href="styles/style.css">
    <!-- Adds the OpenSOS Icon to title bar -->
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>

<body>
    <header>
        <?php
            $page_title = "Apply"; // Set the page title
            include 'header.inc'; // Include the header file
        ?>
        <?php include 'nav.inc' ?> <!--Include Navigation Bar-->
    </header>

    <!-- Begin the main content of the web page -->
    <main>
        <!-- Begin the form. The form submits to the process_eoi.php page -->
         <!-- The form inputs have been modified to automatically fill with any information that has already been entered
              This means that if the user comes back to this page from the process_eoi page becuase there was errors in their submission
              the input they already entered will be repopulated -->
        <form action="process_eoi.php" method="post" novalidate="novalidate">
            <!-- A hidden field used to check if the form has been submitted -->
            <input type="hidden" name="form_submitted" value="1">
            <br>
            <h2 id="apply_subheading">Please enter the relevant information for your application</h2>
            <br>
            <!-- The drop down box for the job reference number -->
            <fieldset id="job_selection">
                    <label for="job_ref_number"><strong>Job Reference Number:</strong>
                    <select name="job_ref_number" id="job_ref_number" required>
                        <option value="" <?php echo !isset($form_data['job_ref_number']) ? 'selected="selected"' : ''; ?>>Please Select</option>
                        <?php
                            // Get the job reference numbers from the database and display them in the dropdown box
                            if ($db_conn) { // Check if $db_conn is valid
                                $query = "SELECT * from jobs";
                                $result = mysqli_query($db_conn, $query);
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result))
                                    {
                                        $selected = isset($form_data['job_ref_number']) && $form_data['job_ref_number'] === $row['reference_code'] ? 'selected="selected"' : '';
                                        echo '<option value="' . htmlspecialchars($row['reference_code']) . '" ' . $selected . '>' . htmlspecialchars($row['reference_code']) . ' (' . htmlspecialchars($row['job_title']) . ')</option>';
                                    }
                                }
                            }
                        ?>
                    </select>
                    </label>
            </fieldset>
            <br><br>

            <!-- Fieldset to contain the applicant details -->
            <!-- Added class "login_container" for similar card styling -->
            <fieldset id="applicant_details" class="login_container">
                <div class="login_form">
                    <!-- Get applicants personal details -->
                    <h2>Personal Details:</h2>

                    <div class="form_row">
                        <label for="given_name">Given Name:</label>
                        <input type="text" id="given_name" name="given_name" pattern="[a-zA-Z]{1,20}"
                               value="<?php echo isset($form_data['given_name']) ? htmlspecialchars($form_data['given_name']) :
                               (isset($user_data['first_name']) ? htmlspecialchars($user_data['first_name']) : ''); ?>" required>
                    </div>

                    <div class="form_row">
                        <label for="family_name">Family Name:</label>
                        <input type="text" id="family_name" name="family_name" pattern="[a-zA-Z]{1,20}"
                            value="<?php echo isset($form_data['family_name']) ? htmlspecialchars($form_data['family_name']) :
                            (isset($user_data['last_name']) ? htmlspecialchars($user_data['last_name']) : ''); ?>" required>
                    </div>

                    <div class="form_row">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" max="2025-03-27"
                            value="<?php echo isset($form_data['dob']) ? htmlspecialchars($form_data['dob']) :
                            (isset($user_data['dob']) ? htmlspecialchars($user_data['dob']) : ''); ?>" required>
                    </div>

                    <div class="form_row"> <!-- Gender Row -->
                        <label>Gender:</label> <!-- Main label for the row -->
                        <div id="gender_registration">
                            <label><input type="radio" name="gender"
                                value="Female" <?php echo (isset($form_data['gender']) && $form_data['gender'] === 'Female') || (!isset($form_data['gender']) && isset($user_data['gender']) && $user_data['gender'] === 'Female') ? 'checked' : ''; ?> required> Female</label>
                            <label><input type="radio" name="gender"
                                value="Male" <?php echo (isset($form_data['gender']) && $form_data['gender'] === 'Male') || (!isset($form_data['gender']) && isset($user_data['gender']) && $user_data['gender'] === 'Male') ? 'checked' : ''; ?>> Male</label>
                            <label><input type="radio" name="gender"
                                value="Other" <?php echo (isset($form_data['gender']) && $form_data['gender'] === 'Other') || (!isset($form_data['gender']) && isset($user_data['gender']) && $user_data['gender'] === 'Other') ? 'checked' : ''; ?>> Other</label>
                            <label><input type="radio" name="gender"
                                value="Prefer Not To Say" <?php echo (isset($form_data['gender']) && $form_data['gender'] === 'Prefer Not To Say') || (!isset($form_data['gender']) && isset($user_data['gender']) && $user_data['gender'] === 'Prefer Not To Say') ? 'checked' : ''; ?>> Prefer Not To Say</label>
                        </div>
                    </div>

                    <!-- Get the address details -->
                    <div class="form_row">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" placeholder="1/110 John street" pattern="[\da-zA-Z/]{1,40}"
                            value="<?php echo isset($form_data['address']) ? htmlspecialchars($form_data['address']) :
                                (isset($user_data['address']) ? htmlspecialchars($user_data['address']) : ''); ?>">
                    </div>

                    <div class="form_row">
                        <label for="suburb">Suburb:</label>
                        <input type="text" id="suburb" name="suburb" pattern="[a-zA-Z ]{1,40}"
                            value="<?php echo isset($form_data['suburb']) ? htmlspecialchars($form_data['suburb']) :
                                (isset($user_data['suburb']) ? htmlspecialchars($user_data['suburb']) : ''); ?>" required>
                    </div>

                    <div class="form_row">
                        <label for="postcode">Postcode:</label>
                        <input type="text" id="postcode" name="postcode" pattern="\d{4}"
                            value="<?php echo isset($form_data['postcode']) ? htmlspecialchars($form_data['postcode']) :
                                (isset($user_data['postcode']) ? htmlspecialchars($user_data['postcode']) : ''); ?>"  required>
                    </div>

                    <div class="form_row">
                        <label for="state">State/Territory:</label>
                        <select name="state" id="state" required>
                            <option value="" <?php echo !isset($form_data['state']) && !isset($user_data['state']) ? 'selected="selected"' : ''; ?>>Please Select</option>
                            <option value="ACT" <?php echo (isset($form_data['state']) && $form_data['state'] === 'ACT') || (!isset($form_data['state']) && isset($user_data['state']) && $user_data['state'] === 'ACT') ? 'selected="selected"' : ''; ?>>ACT</option>
                            <option value="NSW" <?php echo (isset($form_data['state']) && $form_data['state'] === 'NSW') || (!isset($form_data['state']) && isset($user_data['state']) && $user_data['state'] === 'NSW') ? 'selected="selected"' : ''; ?>>NSW</option>
                            <option value="NT" <?php echo (isset($form_data['state']) && $form_data['state'] === 'NT') || (!isset($form_data['state']) && isset($user_data['state']) && $user_data['state'] === 'NT') ? 'selected="selected"' : ''; ?>>NT</option>
                            <option value="QLD" <?php echo (isset($form_data['state']) && $form_data['state'] === 'QLD') || (!isset($form_data['state']) && isset($user_data['state']) && $user_data['state'] === 'QLD') ? 'selected="selected"' : ''; ?>>QLD</option>
                            <option value="SA" <?php echo (isset($form_data['state']) && $form_data['state'] === 'SA') || (!isset($form_data['state']) && isset($user_data['state']) && $user_data['state'] === 'SA') ? 'selected="selected"' : ''; ?>>SA</option>
                            <option value="TAS" <?php echo (isset($form_data['state']) && $form_data['state'] === 'TAS') || (!isset($form_data['state']) && isset($user_data['state']) && $user_data['state'] === 'TAS') ? 'selected="selected"' : ''; ?>>TAS</option>
                            <option value="VIC" <?php echo (isset($form_data['state']) && $form_data['state'] === 'VIC') || (!isset($form_data['state']) && isset($user_data['state']) && $user_data['state'] === 'VIC') ? 'selected="selected"' : ''; ?>>VIC</option>
                            <option value="WA" <?php echo (isset($form_data['state']) && $form_data['state'] === 'WA') || (!isset($form_data['state']) && isset($user_data['state']) && $user_data['state'] === 'WA') ? 'selected="selected"' : ''; ?>>WA</option>
                        </select>
                    </div>

                    <!-- Get the applicants contact details -->
                    <div class="form_row">
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" placeholder="03 1234 5678" pattern="[0-9 ]{8,12}"
                            value="<?php echo isset($form_data['phone_number']) ? htmlspecialchars($form_data['phone_number']) :
                                (isset($user_data['phone_number']) ? htmlspecialchars($user_data['phone_number']) : ''); ?>" required>
                    </div>

                    <div class="form_row">
                        <label for="email">Email Address:</label>
                        <input type="text" id="email" name="email" placeholder="email@domain.com" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" title="Invalid email address"
                            value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) :
                                (isset($user_data['email']) ? htmlspecialchars($user_data['email']) : ''); ?>" required>
                    </div>
                </div> 
            </fieldset>

            <!-- Fieldset to contain the technical skills checklist -->
            <fieldset id="technical_skills">
                <h2>Technical Skills:</h2>
                <br>
                <!-- CHECKBOX input -->
                <section id="technical_skills_input">
                    <!-- Frontend Web Dev Skills -->
                    <section id="frontend_skills" class="checkbox_skills">
                        <h4>Frontend</h4>
                        <label for="html">
                            <input type="checkbox" id="html" name="skills[]" value="html"
                                <?php
                                    // If the form was submitted, only check if 'html' is in the array
                                    if (isset($form_data['form_submitted']))
                                    {
                                        if (isset($form_data['skills']) && is_array($form_data['skills']) && in_array('html', $form_data['skills']))
                                        {
                                            echo 'checked';
                                        }
                                        // If form was submitted and 'html' is not in skills, do NOT check
                                    }
                                    else
                                    {
                                        // If the form has NOT been submitted, check by default
                                        echo 'checked';
                                    }
                                ?>
                            > HTML
                        </label>
                        <label for="css" ><input type="checkbox" id="css" name="skills[]" value="css" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('css', $form_data['skills']) ? 'checked' : ''; ?>> CSS</label>
                        <label for="javascript" ><input type="checkbox" id="javascript" name="skills[]" value="javascript" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('javascript', $form_data['skills']) ? 'checked' : ''; ?>> JavaScript</label>
                        <label for="reactjs" ><input type="checkbox" id="reactjs" name="skills[]" value="reactjs" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('reactjs', $form_data['skills']) ? 'checked' : ''; ?>> React.js</label>
                        <label for="angular" ><input type="checkbox" id="angular" name="skills[]" value="angular" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('angular', $form_data['skills']) ? 'checked' : ''; ?>> Angular</label>
                        <label for="bootstrap" ><input type="checkbox" id="bootstrap" name="skills[]" value="bootstrap" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('bootstrap', $form_data['skills']) ? 'checked' : ''; ?>> Bootstrap</label>
                    </section>
                    <!-- Backend Web Dev Skills -->
                    <section id="backend_skills" class="checkbox_skills">
                        <h4>Backend</h4>
                        <label for="php" ><input type="checkbox" id="php" name="skills[]" value="php" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('php', $form_data['skills']) ? 'checked' : ''; ?>> PHP</label>
                        <label for="phpmyadmin" ><input type="checkbox" id="phpmyadmin" name="skills[]" value="phpmyadmin" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('phpmyadmin', $form_data['skills']) ? 'checked' : ''; ?>> PHPMyAdmin</label>
                        <label for="mysql" ><input type="checkbox" id="mysql" name="skills[]" value="mysql" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('mysql', $form_data['skills']) ? 'checked' : ''; ?>> MySQL</label>
                        <label for="postgresql" ><input type="checkbox" id="postgresql" name="skills[]" value="postgresql" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('postgresql', $form_data['skills']) ? 'checked' : ''; ?>> PostgreSQL</label>
                        <label for="mongodb" ><input type="checkbox" id="mongodb" name="skills[]" value="mongodb" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('mongodb', $form_data['skills']) ? 'checked' : ''; ?>> MongoDB</label>
                        <label for="apache" ><input type="checkbox" id="apache" name="skills[]" value="apache" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('apache', $form_data['skills']) ? 'checked' : ''; ?>> Apache</label>
                        <label for="nodejs" ><input type="checkbox" id="nodejs" name="skills[]" value="nodejs" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('nodejs', $form_data['skills']) ? 'checked' : ''; ?>> Node.js</label>
                    </section>
                    <!-- Programming Skills -->
                    <section id="programming_skills" class="checkbox_skills">
                        <h4>Programming</h4>
                        <label for="python" ><input type="checkbox" id="python" name="skills[]" value="python" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('python', $form_data['skills']) ? 'checked' : ''; ?>> Python</label>
                        <label for="csharp" ><input type="checkbox" id="csharp" name="skills[]" value="csharp" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('csharp', $form_data['skills']) ? 'checked' : ''; ?>> C#</label>
                        <label for="cplusplus" ><input type="checkbox" id="cplusplus" name="skills[]" value="cplusplus" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('cplusplus', $form_data['skills']) ? 'checked' : ''; ?>> C++</label>
                        <label for="ruby" ><input type="checkbox" id="ruby" name="skills[]" value="ruby" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('ruby', $form_data['skills']) ? 'checked' : ''; ?>> Ruby</label>
                        <label for="java" ><input type="checkbox" id="java" name="skills[]" value="java" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('java', $form_data['skills']) ? 'checked' : ''; ?>> Java</label>
                        <label for="pascal" ><input type="checkbox" id="pascal" name="skills[]" value="pascal" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('pascal', $form_data['skills']) ? 'checked' : ''; ?>> Pascal</label>
                        <label for="swift" ><input type="checkbox" id="swift" name="skills[]" value="swift" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('swift', $form_data['skills']) ? 'checked' : ''; ?>> Swift</label>
                    </section>
                    <!-- Generic Skills -->
                    <section id="generic_skills" class="checkbox_skills">
                        <h4>General</h4>
                        <label for="word" ><input type="checkbox" id="word" name="skills[]" value="word" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('word', $form_data['skills']) ? 'checked' : ''; ?>> Word</label>
                        <label for="excel" ><input type="checkbox" id="excel" name="skills[]" value="excel" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('excel', $form_data['skills']) ? 'checked' : ''; ?>> Excel</label>
                        <label for="powerpoint" ><input type="checkbox" id="powerpoint" name="skills[]" value="powerpoint" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('powerpoint', $form_data['skills']) ? 'checked' : ''; ?>> Powerpoint</label>
                        <label for="msteams" ><input type="checkbox" id="msteams" name="skills[]" value="msteams" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('msteams', $form_data['skills']) ? 'checked' : ''; ?>> MS Teams</label>
                        <label for="git" ><input type="checkbox" id="git" name="skills[]" value="git" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('git', $form_data['skills']) ? 'checked' : ''; ?>> Git</label>
                        <label for="jira" ><input type="checkbox" id="jira" name="skills[]" value="jira" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('jira', $form_data['skills']) ? 'checked' : ''; ?>> Jira</label>
                        <label for="trello" ><input type="checkbox" id="trello" name="skills[]" value="trello" <?php echo isset($form_data['skills']) && is_array($form_data['skills']) && in_array('trello', $form_data['skills']) ? 'checked' : ''; ?>> Trello</label>
                    </section>
                </section>
            </fieldset>

            <fieldset id="other_skills_section">
                <!-- Keep checkbox first -->
                <input type="checkbox" id="other_skills_checkbox" name="other_skills_checkbox" <?php echo isset($form_data['other_skills_checkbox']) ? 'checked' : ''; ?>>
                <label for="other_skills_checkbox">I have other relevant skills</label>
                <!-- Textarea appears when checkbox is checked -->
                <div id="other_skills_textarea_container">
                    <label for="other_skills">Please enter any other relevant skills:</label><br>
                    <textarea name="other_skills" id="other_skills" rows="10" placeholder="List any other relevant skills here"><?php echo isset($form_data['other_skills']) ? htmlspecialchars($form_data['other_skills']) : ''; ?></textarea>
                </div>
            </fieldset>

            <fieldset id="buttons_div">
                <!-- The Submit button -->
                <input class="buttons" type="submit" value="Apply">
                <!-- The Reset button -->
                <input class="buttons" type="reset" value="Reset">
            </fieldset>
        </form>
    </main>

    <?php include 'footer.inc' ?> <!--Include Footer-->

</body>
</html>