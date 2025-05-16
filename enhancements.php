<?php
session_start();
require_once 'settings.php'; // Ensure this file correctly initializes $conn

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

// Error message variables for the current request (will be stored in session)
$error = "";
$time_left_message = "";

// Variable to check if its user or manager 
$user_type = ""; // This will be set to either 'user' or 'manager' based on the login attempt

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = trim($_POST['username']);
    $input_password = trim($_POST['password']);

    $current_lockout_time = getLockoutTimeLeft(); // Check current lockout status

    if ($current_lockout_time > 0) {
        // This error is set if user tries to submit while already locked.
        $error = "Account is locked.";
        $time_left_message = "Please try again in {$current_lockout_time} second(s).";
    } else {
        // Not currently locked out, proceed with login attempt

        // Check managers table (
        $query= "SELECT * FROM managers WHERE username = '$input_username' AND password = '$input_password'";
        $result = mysqli_query($db_conn, $query);

        if ($user = mysqli_fetch_assoc($result)) {
            $_SESSION['username'] = $input_username;
            $_SESSION['login_attempts'] = 0; // Reset attempts
            $_SESSION['lockout_time'] = 0;   // Reset lockout time
            $_SESSION['user_type'] = 'manager'; // Set manager type
            unset($_SESSION['error']);       // Clear any previous error messages
            unset($_SESSION['time_left_message']); // Clear any previous time_left messages
            header("Location: manage.php");
            exit();
        }

        // Check users table 
        $query= "SELECT * FROM users WHERE username = '$input_username' AND password = '$input_password'";
        $result = mysqli_query($db_conn, $query);

        if ($user= mysqli_fetch_assoc($result)) {
            $_SESSION['username'] = $input_username;
            $_SESSION['login_attempts'] = 0; // Reset attempts
            $_SESSION['lockout_time'] = 0;   // Reset lockout time
            $_SESSION['user_type'] = 'user'; // Set user type
            unset($_SESSION['error']);       // Clear any previous error messages
            unset($_SESSION['time_left_message']); // Clear any previous time_left messages
            header("Location: index.php");
            exit();
        }

        // Invalid credentials if neither manager nor user found
        $_SESSION['login_attempts']++;

        // Maximum login attempts reached
        if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            $_SESSION['lockout_time'] = time(); // Set lockout time
            $current_lockout_time = getLockoutTimeLeft(); // This will be LOCKOUT_DURATION_SECONDS
            $error = "Account locked.";
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
$is_currently_locked_out = ($current_lockout_time > 0);

if ($is_currently_locked_out) {
    //Refresh the lockout message if still locked out
    $_SESSION['time_left_message'] = "Please try again in {$current_lockout_time} second(s).";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <<meta charset="UTF-8">
    <meta name="description" content="Project Part 1 enhancements.php Page (Login Page)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML5, Group Project, Home Page, OpenSOS">
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
    <div class="login_container">
        <h2>Login here!</h2>

        <!-- Display error messages from session -->
        <?php
            // Messages are now cleared by getLockoutTimeLeft() on expiry or by successful login.
            // They will persist in session across refreshes if the condition is still active.
            if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                echo '<p class="error_message">' . htmlspecialchars($_SESSION['error']) . '</p>';
            }
            if (isset($_SESSION['time_left_message']) && !empty($_SESSION['time_left_message'])) {
                echo '<p class="error_message">' . htmlspecialchars($_SESSION['time_left_message']) . '</p>';
            }
        ?>

        <!--Login form -->
        <form class="login_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <input type="text" id="username" name="username" placeholder="Username" required
            <?php echo $is_currently_locked_out ? 'disabled' : ''; ?>>

            <input type="password" id="password" name="password" placeholder="Password" required
            <?php echo $is_currently_locked_out ? 'disabled' : ''; ?>>

            <button type="submit" class="login_button" <?php echo $is_currently_locked_out ? 'disabled' : ''; ?>>
                Login
            </button>
        </form>
    </div>

    <!--Register options -->
    <div class="register_options">
        <h2>New User? Register your credentials here!</h2>
        <button type="button" class="login_button" onclick="window.location.href='register_user.php'">User Register</button>
        <button type="button" class="login_button" onclick="window.location.href='register_manager.php'">Manager Register</button>
    </div>
    </main>
    <footer>
        <?php include 'footer.inc'; ?>
    </footer>
</body>
</html>
