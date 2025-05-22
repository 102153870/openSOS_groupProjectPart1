<!--USER REGISTRATION PAGE-->
<!--In-charge: Rodney/Henry-->
<?php
session_start(); 
require_once 'settings.php';

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitise_input($_POST['email']);
    $username = sanitise_input($_POST['username']);
    $retype_password = sanitise_input($_POST['retype_password']);
    $password = sanitise_input($_POST['password']);

    //using filter_var to validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_user_register'] = "Invalid email format."; //Use session to persist error message after redirect
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

    //Check if the email already exists in the database
    if ($result->num_rows > 0) {
        $_SESSION['error_user_register'] = "Email is already registered.";
        header("Location: register_user.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // The optional fields. Use ternary operators to set it to null if no text is entered
    $given_name = isset($_POST['given_name_registration']) ? $_POST['given_name_registration'] : null;
    $family_name = isset($_POST['family_name_registration']) ? $_POST['family_name_registration'] : null;
    $dob = isset($_POST['dob_registration']) ? $_POST['dob_registration'] : null;
    $gender = isset($_POST['gender_registration']) ? $_POST['gender_registration'] : null;
    $address = isset($_POST['address_registration']) ? $_POST['address_registration'] : null;
    $suburb = isset($_POST['suburb_registration']) ? $_POST['suburb_registration'] : null;
    $postcode = isset($_POST['postcode_registration']) ? $_POST['postcode_registration'] : null;
    $state = isset($_POST['state_registration']) ? $_POST['state_registration'] : null;
    $phone = isset($_POST['phone_number_registration']) ? $_POST['phone_number_registration'] : null;

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
        $_SESSION['success_user_register'] = "User registered successfully! You may now <a href=\"login.php\">LOGIN</a>.";
        header("Location: register_user.php");
    } else {
        $_SESSION['error_user_register'] = "Registration failed. Try again.";
        header("Location: register_user.php");
    }

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

    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>

<body>
    <header>
        <?php
            $page_title = "User Register";
            include 'header.inc'; // Ensure this path is correct
        ?>
    </header>

    <main>
    <div class="login_container">
        <h2>Register here!</h2>

        <!--Registration form -->
        <form class="login_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <?php
            if (isset($_SESSION['error_user_register'])) {
                echo "<p class='error_message'>" . $_SESSION['error_user_register'] . "</p>";
                unset($_SESSION['error_user_register']);
            } elseif (isset($_SESSION['success_user_register'])) {
                echo "<p class='success_message'>" . $_SESSION['success_user_register'] . "</p>";
                unset($_SESSION['success_user_register']);
            }
            ?>

            <label for="email" class="visually_hidden">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">

            <label for="password" class="visually_hidden">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="retype_password" name="retype_password" placeholder="Retype Password" required>

            <label for="username" class="visually_hidden">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username" required>
                
            <!-- Optional details to simplify user applications -->
            <h2>Optional Information</h2>
            <h4>Personal Details:</h4>
            <!-- Max 20 alpha characters -->
            <label for="given_name_registration"><input type="text" id="given_name_registration" name="given_name_registration" pattern="[a-zA-Z]{1,20}" placeholder="Given Name"></label>
            <!-- Max 20 alpha characters -->
            <label for="family_name_registration"><input type="text" id="family_name_registration" name="family_name_registration" pattern="[a-zA-Z]{1,20}"  placeholder="Family Name"></label>
            <h4>Date of Birth:</h4>
            <label for="dob_registration"><input type="date" id="dob_registration" name="dob_registration" max="2025-03-27"></label>
            <h4>Gender:</h4>
            <fieldset id="gender_selection_registration">
                <label><input type="radio" name="gender_registration" value="Female"> Female</label>
                <label><input type="radio" name="gender_registration" value="Male"> Male</label>
                <label><input type="radio" name="gender_registration" value="Other"> Other</label>
                <label><input type="radio" name="gender_registration" value="Prefer Not To Say"> Prefer Not To Say</label>
            </fieldset>
            
            <h4>Address:</h4>
            <!-- Max 40 alphanumeric characters for address, allows slashes (/) and dashes (-) as well -->
            <label for="address_registration"><input type="text" id="address_registration" name="address_registration" placeholder="1/110 John street" pattern="[\da-zA-Z/]{1,40}"></label>
            <!-- Max 40 characters for address, only allows characters -->
            <label for="suburb_registration"><input type="text" id="suburb_registration" name="suburb_registration" placeholder="Suburb" pattern="[a-zA-Z ]{1,40}"></label>
            <!-- 4 digits -->
            <label for="postcode_registration"><input type="text" id="postcode_registration" name="postcode_registration" placeholder="Postcode" pattern="\d{4}"></label>
            <!-- Dropdown menu for the state/territory -->
            <h4>State/Territory</h4>
            <label for="state_registration">
                <select name="state_registration" id="state_registration">
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
            <br><br>

            <h4>Phone Number:</h4>
            <label for="phone_number_registration">
                <!-- Input validation is for 8 to 12 digits, or spaces -->
                <input type="text" id="phone_number_registration" name="phone_number_registration" placeholder="03 1234 5678" pattern="[0-9 ]{8,12}">
            </label>

            <button type="submit" class="login_button">
                Register
            </button>
        </form>
    </div>
    </main>
    <footer>
        <?php include 'footer.inc'; ?>
    </footer>
</body>
</html>