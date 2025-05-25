<?php
session_start();
require_once 'settings.php'; // Ensure this file correctly initializes $db_conn

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

//Security check to see if user is already logged in:
if(isset($_SESSION['role'])){
    if($_SESSION['role'] == 'user'){
        header("Location: profile.php");
    }
    else{
        header("Location: manage.php");
    }
}


// --- Constants for login logic ---
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_DURATION_SECONDS', 10); // Lockout time in seconds


// Initialize session variables if not already set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = 0;
}

// Function to check lockout status and time left
function getLockoutTimeLeft() {
    if (isset($_SESSION['lockout_time']) && $_SESSION['lockout_time'] > 0) {
        $time_passed = time() - $_SESSION['lockout_time'];
        $time_left = LOCKOUT_DURATION_SECONDS - $time_passed;
        if ($time_left > 0) {
            return $time_left; // Still locked out
        } else {
            // Lockout expired, reset state
            $_SESSION['lockout_time'] = 0;
            $_SESSION['login_attempts'] = 0;
            unset($_SESSION['error']); // Clear error message related to lockout/attempts
            unset($_SESSION['time_left_message']); // Clear time_left message
            return 0; // Lockout just expired
        }
    }
    return 0; // Not locked out or lockout_time not set
}

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

// Error message variables for the current request (will be stored in session)
$error = "";
$time_left_message = "";

// Variable to check if its user or manager 
$role = ""; // This will be set to either 'user' or 'manager' based on the login attempt

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username_or_email = sanitise_input($_POST['username_or_email']);
    $input_password = sanitise_input($_POST['password']);

    $current_lockout_time = getLockoutTimeLeft(); // Check current lockout status

    if ($current_lockout_time > 0) {
        // This error is set if user tries to submit while already locked.
        $error = "Login is locked.";
        $time_left_message = "Please try again in {$current_lockout_time} second(s).";
    } else {
        // Not currently locked out, proceed with login attempt

        // Check for email or username in the database
        $query = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = mysqli_prepare($db_conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $input_username_or_email, $input_username_or_email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Result is found in the users table
        if ($user = mysqli_fetch_assoc($result)) 
        {
            // Check password
            if (password_verify($input_password, $user['password'])) 
            {
                session_regenerate_id(true); // Regenerate session ID to prevent session fixation attacks
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['login_attempts'] = 0;
                $_SESSION['lockout_time'] = 0;
                $_SESSION['role'] = $user['role'];

                unset($_SESSION['error']);
                unset($_SESSION['time_left_message']);

                // Redirect based on role
                if ($user['role'] == 'manager') header("Location: manage.php");
                else 
                {//Role is user
                    // Save the user data that can be pre filled in the apply page
                    $_SESSION['user_data'] = array(
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'dob' => $user['dob'],
                        'gender' => $user['gender'],
                        'address' => $user['address'],
                        'suburb' => $user['suburb'],
                        'state' => $user['state'],
                        'postcode' => $user['postcode'],
                        'phone_number' => $user['phone_number'],
                        'email' => $user['email']
                    );
                    header("Location: index.php");
                }

                exit();
            }
        }

        // Invalid credentials if neither manager nor user found
        $_SESSION['login_attempts']++;

        // Maximum login attempts reached
        if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            $_SESSION['lockout_time'] = time(); // Set lockout time
            $current_lockout_time = getLockoutTimeLeft(); // This will be LOCKOUT_DURATION_SECONDS
            $error = "Login locked.";
            $time_left_message = "Please try again in {$current_lockout_time} second(s).";
        } else {
            // Show remaining attempts
            $remaining_attempts = MAX_LOGIN_ATTEMPTS - $_SESSION['login_attempts'];
            $error = "Invalid credentials.";
            $time_left_message = "$remaining_attempts attempt" . ($remaining_attempts !== 1 ? "s" : "") . " remaining.";
            $_SESSION['lockout_time'] = 0; // Ensure lockout_time is not set if not actually locked
        }
    }

    // Save messages to session
    $_SESSION['error'] = $error;
    $_SESSION['time_left_message'] = $time_left_message;

    // Redirect to the same page to display error messages and prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Call getLockoutTimeLeft() here on every page load (POST or GET).
$current_lockout_time = getLockoutTimeLeft(); 
$is_currently_locked_out = ($current_lockout_time > 0); //If lockout time is greater than 0, user is locked out

// Set the error message if the user is currently locked out
if ($is_currently_locked_out) {
    //Refresh the lockout message if still locked out
    $_SESSION['time_left_message'] = "Please try again in {$current_lockout_time} second(s).";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 2 login.php Page (Login Page)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML5, Group Project, Login Page, OpenSOS">
    <meta name="author" content="Rodney Liaw">
    <title>User Login</title>

    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>
<body>
    <header>
        <?php
            $page_title = "Login Page";
            include 'header.inc'; // Ensure this path is correct
        ?>
    </header>
   <main>
    <?php include 'nav.inc'?>
    <?php
    // Messages are now cleared by getLockoutTimeLeft() on expiry or by successful login.
    // They will persist in session across refreshes if the condition is still active.

        // Display error messages from session
        if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
            echo '<p class="error_message">' . htmlspecialchars($_SESSION['error']) . '</p>';
        }
        if (isset($_SESSION['time_left_message']) && !empty($_SESSION['time_left_message'])) {
                    echo '<p class="error_message">' . htmlspecialchars($_SESSION['time_left_message']) . '</p>';
        }
    ?>
    <div class="login_container">
        <h2>Login here!</h2> <br>

        <!--Login form -->
        <form class="login_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form_row"><!--Username or Email -->
                <label for="username_or_email">Username/Email:</label>
                <input type="text" id="username_or_email" name="username_or_email"
                    placeholder="Username or Email" required
                    <?php echo $is_currently_locked_out ? 'disabled' : ''; ?>> <!--If lockout is active, the input field is disabled-->
            </div>

            <div class="form_row"><!--Password-->
                <label for="password">Password:</label>
                <input type="password" id="password" name="password"
                    placeholder="Password" required
                    <?php echo $is_currently_locked_out ? 'disabled' : ''; ?>><!--If lockout is active, the input field is disabled-->
            </div>

            <!--Login button-->
            <button type="submit" class="login_button" <?php echo $is_currently_locked_out ? 'disabled' : ''; ?>> <!--If lockout is active, the button is disabled-->
                Login
            </button>
        </form>
    </div>

    <!--Register options -->
    <div class="register_options">
        <h2>New User? Register your credentials here!</h2>
        <a href='register_user.php'><button type="button" class="login_button">User Register</button></a>
        <a href='register_manager.php'><button type="button" class="login_button" >Manager Register</button></a>
    </div>
    </main>
    <footer>
        <?php include 'footer.inc'; ?>
    </footer>
</body>
</html>
