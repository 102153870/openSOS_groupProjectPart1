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
        <form action="process_eoi.php" method="post" novalidate="novalidate">
            <br>
            <!-- The drop down box for the job reference number -->
            <fieldset id="job_selection">
                <h2 id="apply_subheading">Please enter the relevant information for your application</h2>
                <br>
                <label for="job_ref_number"><strong>Job Reference Number:</strong>
                    <select name="job_ref_number" id="job_ref_number" required>
                        <option value="" selected="selected">Please Select</option>
                        <option value="H3110">H3110 (Data Analyst)</option>
                        <option value="T4B13">T4B13 (Programmer)</option>
                        <option value="P1224">P1224 (Front-end Web Developer)</option>
                    </select>
                </label>
            </fieldset>
            <br><br>

            <!-- Fieldset to contain the applicant details -->
            <fieldset id="applicant_details">
                <!-- Get applicants personal details -->
                <section id="personal_details" class="input">
                    <!-- The 'title' of the section -->
                    <h3>Personal Details:</h3>
                    <br>
                    <!-- Max 20 alpha characters. Has inline CSS!! -->
                    <p><label for="given_name">Given Name: <input type="text" id="given_name" name="given_name" pattern="[a-zA-Z]{1,20}" style="<?php echo $error ?  'border:2px solid red;' : ''; ?>" required></label></p>
                    <br>
                    <!-- Max 20 alpha characters -->
                    <label for="family_name">Family Name: <input type="text" id="family_name" name="family_name" pattern="[a-zA-Z]{1,20}" required></label>
                    <br>
                    <label for="dob">Date of Birth: <input type="date" id="dob" name="dob" max="2025-03-27" required></label>
                    <br>
                    <fieldset id="gender_selection">
                        <legend>Gender:</legend>
                        <label><input type="radio" name="gender" value="Female"> Female</label>
                        <label><input type="radio" name="gender" value="Male"> Male</label>
                        <label><input type="radio" name="gender" value="Other"> Other</label>
                        <label><input type="radio" name="gender" value="Prefer Not To Say"> Prefer Not To Say</label>
                    </fieldset>
                    <br>
                </section>

                <!-- Get the address details -->
                <section id="address_details" class="input">
                    <h3>Address:</h3>
                    <br>
                    <!-- Max 40 alphanumeric characters for address, allows slashes (/) and dashes (-) as well -->
                    <label for="address">Address: <input type="text" id="address" name="address" placeholder="1/110 John street" pattern="[\da-zA-Z/]{1,40}"></label>
                    <br>
                    <!-- Max 40 characters for address, only allows characters -->
                    <label for="suburb">Suburb: <input type="text" id="suburb" name="suburb" pattern="[a-zA-Z]{1,40}"></label>
                    <br>
                    <!-- 4 digits -->
                    <label for="postcode">Postcode: <input type="text" id="postcode" name="postcode" pattern="\d{4}" required></label>
                    <br>
                    <!-- Dropdown menu for the state/territory -->
                    <label for="state">State/Territory
                        <select name="state" id="state" required>
                            <option value="" selected="selected">Please Select</option>
                            <option value="act">ACT</option>
                            <option value="nsw">NSW</option>
                            <option value="nt">NT</option>
                            <option value="qld">QLD</option>
                            <option value="sa">SA</option>
                            <option value="tas">TAS</option>
                            <option value="vic">VIC</option>
                            <option value="wa">WA</option>
                        </select>
                    </label>
                </section>
                <br>

                <!-- Get the applicants contact details -->
                <section id="contact_details" class="input">
                    <h3>Contact Details:</h3>
                    <br>
                    <label for="phone_number">Phone Number:
                        <!-- Input validation is for 8 to 12 digits, or spaces -->
                        <input type="text" id="phone_number" name="phone_number" placeholder="03 1234 5678" pattern="[0-9 ]{8,12}" required>
                    </label>
                    <br>
                    <label for="email">Email Address:
                        <!-- Cannot use type email -->
                        <!-- ([^@\s]+) Make sure there are one or more characters that are not whitespace or the @ symbol
                             (@) Make sure the @ symbol comes next
                             ([^@\s]+) Make sure there is at least one character that is not whitespace or @ symbol
                             (\.) Make sure the dot is next
                             ([^@\s]+) Make sure there is at least one character that is not whitespace or @ symbol -->
                        <input type="text" id="email" name="email" placeholder="email@domain.com" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" title="Invalid email address" required>
                        <!-- <input type="email" id="email" name="email" placeholder="email@domain.com" required> -->
                    </label>
                </section>
                <br>
            </fieldset>

            <!-- Fieldset to contain the technical skills checklist -->
            <fieldset id="technical_skills">
                <h3>Technical Skills:</h3>
                <br>
                <!-- CHECKBOX input -->
                <section id="technical_skills_input">
                    <!-- Frontend Web Dev Skills -->
                    <section id="frontend_skills" class="checkbox_skills">
                        <h4>Frontend</h4>
                        <label for="html" ><input type="checkbox" id="html" name="skills[]" value="html" checked="checked"> HTML</label>
                        <label for="css" ><input type="checkbox" id="css" name="skills[]" value="css"> CSS</label>
                        <label for="javascript" ><input type="checkbox" id="javascript" name="skills[]" value="javascript"> JavaScript</label>
                        <label for="reactjs" ><input type="checkbox" id="reactjs" name="skills[]" value="reactjs"> React.js</label>
                        <label for="angular" ><input type="checkbox" id="angular" name="skills[]" value="angular"> Angular</label>
                        <label for="bootstrap" ><input type="checkbox" id="bootstrap" name="skills[]" value="bootstrap"> Bootstrap</label>
                    </section>
                    <!-- Backend Web Dev Skills -->
                    <section id="backend_skills" class="checkbox_skills">
                        <h4>Backend</h4>
                        <label for="php" ><input type="checkbox" id="php" name="skills[]" value="php"> PHP</label>
                        <label for="phpmyadmin" ><input type="checkbox" id="phpmyadmin" name="skills[]" value="phpmyadmin"> PHPMyAdmin</label>
                        <label for="mysql" ><input type="checkbox" id="mysql" name="skills[]" value="mysql"> MySQL</label>
                        <label for="postgresql" ><input type="checkbox" id="postgresql" name="skills[]" value="postgresql"> PostgreSQL</label>
                        <label for="mongodb" ><input type="checkbox" id="mongodb" name="skills[]" value="mongodb"> MongoDB</label>
                        <label for="apache" ><input type="checkbox" id="apache" name="skills[]" value="apache"> Apache</label>
                        <label for="nodejs" ><input type="checkbox" id="nodejs" name="skills[]" value="nodejs"> Node.js</label>
                    </section>
                    <!-- Programming Skills -->
                    <section id="programming_skills" class="checkbox_skills">
                        <h4>Programming</h4>
                        <label for="python" ><input type="checkbox" id="python" name="skills[]" value="python"> Python</label>
                        <label for="csharp" ><input type="checkbox" id="csharp" name="skills[]" value="csharp"> C#</label>
                        <label for="cplusplus" ><input type="checkbox" id="cplusplus" name="skills[]" value="cplusplus"> C++</label>
                        <label for="ruby" ><input type="checkbox" id="ruby" name="skills[]" value="ruby"> Ruby</label>
                        <label for="java" ><input type="checkbox" id="java" name="skills[]" value="java"> Java</label>
                        <label for="pascal" ><input type="checkbox" id="pascal" name="skills[]" value="pascal"> Pascal</label>
                        <label for="swift" ><input type="checkbox" id="swift" name="skills[]" value="swift"> Swift</label>
                    </section>
                    <!-- Generic Skills -->
                    <section id="generic_skills" class="checkbox_skills">
                        <h4>General</h4>
                        <label for="word" ><input type="checkbox" id="word" name="skills[]" value="word"> Word</label>
                        <label for="excel" ><input type="checkbox" id="excel" name="skills[]" value="excel"> Excel</label>
                        <label for="powerpoint" ><input type="checkbox" id="powerpoint" name="skills[]" value="powerpoint"> Powerpoint</label>
                        <label for="msteams" ><input type="checkbox" id="msteams" name="skills[]" value="msteams"> MS Teams</label>
                        <label for="git" ><input type="checkbox" id="git" name="skills[]" value="git"> Git</label>
                        <label for="jira" ><input type="checkbox" id="jira" name="skills[]" value="jira"> Jira</label>
                        <label for="trello" ><input type="checkbox" id="trello" name="skills[]" value="trello"> Trello</label>
                    </section>
                </section>
            </fieldset>
                
            <fieldset id="other_skills_section">
                <h3>Other Skills:</h3>
                <p>
                    <br>
                    <label><textarea name="other_skills" id="other_skills" rows="10" placeholder="List any other relevant skills here"></textarea>Please Enter Any Other Relevant Skills</label>
                </p>
                </fieldset>
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