<!--USER REGISTRATION PAGE-->
<!--In-charge: Rodney/Henry-->
<?php
session_start();
require_once 'settings.php'; // Ensure this path is correct and $db_conn is initialized

// Check if $db_conn is valid before using
if ($db_conn) {
    // Check if 'users' table exists, if not create it
    $table_check_query = "SHOW TABLES LIKE 'users'";
    $table_check_result = $db_conn->query($table_check_query);

    if ($table_check_result && $table_check_result->num_rows === 0) {
        // Table doesn't exist, so create it
        $create_table_query = "
            CREATE TABLE users (
                user_id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(50) NOT NULL UNIQUE,
                username VARCHAR(50) NOT NULL,
                password VARCHAR(100) NOT NULL,
                role varchar(50) NOT NULL,
                first_name VARCHAR(50),
                last_name VARCHAR(50),
                dob DATE,
                gender VARCHAR(20),
                address VARCHAR(100),
                suburb VARCHAR(50),
                state VARCHAR(10),
                postcode VARCHAR(10),
                phone_number VARCHAR(20),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";

        if (!$db_conn->query($create_table_query)) {
            die("Error creating users table: " . $db_conn->error);
        }
    }
} else {
    die("Database connection failed. Please check your settings.php file.");
}

// Function to sanitise the input (from your original script)
function sanitise_input ($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mandatory fields: Check if set, then sanitise.
    // For passwords, trim is okay, but avoid htmlspecialchars before hashing.
    $email = isset($_POST['email']) ? sanitise_input($_POST['email']) : '';
    $username = isset($_POST['username']) ? sanitise_input($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $retype_password = isset($_POST['retype_password']) ? trim($_POST['retype_password']) : '';

    // Basic check for empty mandatory fields (as these have 'required' in HTML)
    if (empty($email) || empty($username) || empty($password) || empty($retype_password)) {
        $_SESSION['error_user_register'] = "All mandatory fields are required.";
        header("Location: register_user.php");
        exit();
    }

    //using filter_var to validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_user_register'] = "Invalid email format.";
        header("Location: register_user.php");
        exit();
    }
    // Check if the passwords matched
    if ($password != $retype_password) {
        $_SESSION['error_user_register'] = "Passwords did not match.";
        header("Location: register_user.php");
        exit();
    }

    // Check if email already exists (using prepared statement)
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db_conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_user_register'] = "Email is already registered.";
        $stmt->close(); // Close statement
        header("Location: register_user.php");
        exit();
    }
    $stmt->close(); // Close statement

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // The optional fields. Use ternary operators to set it to null if no text is entered.
    // Sanitise if not empty.
    $given_name = !empty($_POST['given_name_registration']) ? sanitise_input($_POST['given_name_registration']) : null;
    $family_name = !empty($_POST['family_name_registration']) ? sanitise_input($_POST['family_name_registration']) : null;
    $dob = !empty($_POST['dob_registration']) ? sanitise_input($_POST['dob_registration']) : null; // Date input doesn't need much sanitization like text
    $gender = !empty($_POST['gender_registration']) ? sanitise_input($_POST['gender_registration']) : null;
    $address = !empty($_POST['address_registration']) ? sanitise_input($_POST['address_registration']) : null;
    $suburb = !empty($_POST['suburb_registration']) ? sanitise_input($_POST['suburb_registration']) : null;
    $postcode = !empty($_POST['postcode_registration']) ? sanitise_input($_POST['postcode_registration']) : null;
    $state = !empty($_POST['state_registration']) ? sanitise_input($_POST['state_registration']) : null;
    $phone = !empty($_POST['phone_number_registration']) ? sanitise_input($_POST['phone_number_registration']) : null;

    // Insert into users table (using prepared statement)
    $insert_query = "INSERT INTO users (email, username, password, role, first_name, last_name, dob, gender, address, suburb, state, postcode, phone_number)
                    VALUES (?, ?, ?, 'user',  ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db_conn->prepare($insert_query);
    $stmt->bind_param("ssssssssssss",
        $email,
        $username,
        $hashed_password,
        $given_name,
        $family_name,
        $dob,
        $gender,
        $address,
        $suburb,
        $state,
        $postcode,
        $phone
    );

    if ($stmt->execute()) {
        $_SESSION['success_user_register'] = "User registered successfully! You may now <a href=\"login.php\" class='registration_message'>LOGIN</a>.";
    } else {
        // Provide error from statement for debugging, but generic for user
        $_SESSION['error_user_register'] = "Registration failed. Try again. Error: " . $stmt->error;
    }
    $stmt->close(); // Close statement
    $db_conn->close(); // Close connection
    header("Location: register_user.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 2 register_user.php Page">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML5, Group Project, Home Page, OpenSOS">
    <meta name="author" content="Rodney Liaw">
    <title>User Registration</title>

    <link rel="stylesheet" href="styles/style.css"> <!-- Ensure this path is correct -->
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png"> <!-- Ensure this path is correct -->
</head>

<body>
    <header>
        <?php
            $page_title = "User Register";
            include 'header.inc';//Include the header
        ?>
    </header>

    <main>
        <?php include 'nav.inc' //Include the nav?> 
        <!-- Single Form wrapping all content, using original class if applicable -->
        <form class="login_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div> <!-- Simple div for messages, no extra inline styles -->
                <?php
                // Display error/success messages once at the top
                if (isset($_SESSION['error_user_register'])) {
                    echo "<p class='error_message'> <strong>" . $_SESSION['error_user_register'] . "</strong></p>";
                    unset($_SESSION['error_user_register']);
                } elseif (isset($_SESSION['success_user_register'])) {
                    echo "<p class='success_message'><strong>" . $_SESSION['success_user_register'] . "</strong></p>";
                    unset($_SESSION['success_user_register']);
                }
                ?>
            </div>

            <div class ="user_registration_container">
                <!--Mandatory Information-->
                <div class="login_container">
                    <!-- Removed individual form tag -->
                    <div class="form_section_left">
                        <h2>Mandatory Information</h2>
                        <div class="form_row">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
                        </div>

                        <div class="form_row">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" placeholder="Password" required>
                        </div>

                        <div class="form_row">
                            <label for="retype_password">Retype Password:</label>
                            <input type="password" id="retype_password" name="retype_password" placeholder="Retype Password" required>
                        </div>

                        <div class="form_row">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" placeholder="Username" required>
                        </div>
                    </div>
                    <!-- Removed individual submit button -->
                </div>

                <!--Optional Information-->
                <div class="login_container">
                    <!-- Removed individual form tag -->
                    <!-- Removed individual message display -->
                    <div class="form_section_right">
                        <h2>Optional Information</h2>
                        <div class="form_row"> <!--Given Name-->
                            <label for="given_name_registration" >Given Name</label>
                            <input type="text" id="given_name_registration" name="given_name_registration" pattern="[a-zA-Z]{1,20}" placeholder="Given Name">
                        </div>

                        <div class="form_row"><!--Family Name-->
                            <label for="family_name_registration" >Family Name</label>
                            <input type="text" id="family_name_registration" name="family_name_registration" pattern="[a-zA-Z]{1,20}" placeholder="Family Name">
                        </div>

                        <div class="form_row"><!--DOB-->
                            <label for="dob_registration">Date of Birth</label>
                            <input type="date" id="dob_registration" name="dob_registration" max="2025-03-27"> <!-- Original max date -->
                        </div>

                        <div class="form_row">
                            <label for="gender_registration_main_label">Gender:</label>
                            <div id="gender_registration">
                                <label><input type="radio" name="gender_registration" value="Female"> Female</label>
                                <label><input type="radio" name="gender_registration" value="Male"> Male</label>
                                <label><input type="radio" name="gender_registration" value="Other"> Other</label>
                                <label><input type="radio" name="gender_registration" value="Prefer Not To Say"> Prefer Not To Say</label>
                            </div>
                        </div>

                        <div class="form_row"><!--Address-->
                            <label for="address_registration">Address</label>
                            <input type="text" id="address_registration" name="address_registration" placeholder="1/110 John street" pattern="[\da-zA-Z/]{1,40}"> <!-- Original pattern -->
                        </div>

                        <div class="form_row"><!--Suburb-->
                            <label for="suburb_registration">Suburb</label>
                            <input type="text" id="suburb_registration" name="suburb_registration" placeholder="Suburb" pattern="[a-zA-Z ]{1,40}">
                        </div>

                        <div class="form_row"><!--Postcode-->
                            <label for="postcode_registration" >Postcode</label>
                            <input type="text" id="postcode_registration" name="postcode_registration" placeholder="Postcode" pattern="\d{4}">
                        </div>

                        <div class="form_row"><!--State-->
                            <label for="state_registration">State/Territory</label>
                            <select name="state_registration" id="state_registration">
                                <option value="" selected="selected">Please Select</option>
                                <option value="ACT">ACT</option>
                                <option value="NSW">NSW</option>
                                <option value="NT">NT</option>
                                <option value="QLD">QLD</option>
                                <option value="SA">SA</option>
                                <option value="TAS">TAS</option>
                                <option value="VIC">VIC</option>
                                <option value="WA">WA</option>
                            </select>
                        </div>

                        <div class="form_row"><!--Phone Number-->
                            <label for="phone_number_registration" >Phone Number</label>
                            <input type="text" id="phone_number_registration" name="phone_number_registration" placeholder="03 1234 5678" pattern="[0-9 ]{8,12}">
                        </div>
                    </div>
                    <!-- Removed individual submit button -->
                </div>
            </div> <!-- End of user_registration_container -->

            <!-- Single Submit Button for the entire form, using original class structure -->
            <div class="profile_container">
                <button type="submit" class="buttons">Register</button>
            </div>
        </form> <!-- End of single form -->
    </main>
    <footer>
        <?php include 'footer.inc'; ?> <!-- Ensure this path is correct -->
    </footer>
</body>
</html>