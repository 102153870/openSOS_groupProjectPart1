<?php
session_start();
require_once 'settings.php';

// Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

// Check if account is locked
function isAccountLocked() {
    $lockout_duration = 300; // 5 minutes lockout
    if ($_SESSION['lockout_time'] > 0) {
        $time_left = $_SESSION['lockout_time'] + $lockout_duration - time();
        if ($time_left > 0) {
            return $time_left;
        }
        // Reset lockout if time has expired
        $_SESSION['lockout_time'] = 0;
        $_SESSION['login_attempts'] = 0;
    }
    return 0;
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

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = sanitise_input($_POST['username']);
    $input_password = sanitise_input($_POST['password']);

    // Check if account is locked
    $lockout_time = isAccountLocked();
    if ($lockout_time > 0) {
        $error = "Account is locked. Please try again in " . ceil($lockout_time / 60) . " minutes.";
    }
    else 
    {
        // Check in managers table first
        $query = "SELECT * FROM managers WHERE username = '$input_username' AND password = '$input_password'";
        $result = mysqli_query($conn, $query);

        if ($user = mysqli_fetch_assoc($result)) {
            $_SESSION['username'] = $input_username;
            $_SESSION['login_attempts'] = 0;
            header("Location: manage.php");
            exit();
        }

        // If not found in managers, check users table
        $query = "SELECT * FROM users WHERE username = '$input_username' AND password = '$input_password'";
        $result = mysqli_query($conn, $query);

        if ($user = mysqli_fetch_assoc($result)) {
            $_SESSION['username'] = $input_username;
            $_SESSION['login_attempts'] = 0;
            header("Location: index.php");
            exit();
        }
        
        else {
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] >= 3) {
                $_SESSION['lockout_time'] = time();
                $error = "Account locked. Please try again in 5 minutes.";
            } else {
                $remaining = 3 - $_SESSION['login_attempts'];
                $error = "Invalid credentials. $remaining attempt" . ($remaining !== 1 ? "s" : "") . " remaining.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Login - OpenSOS</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        .login_container {
            background-color: #b8ebab;
            padding: 2rem;
            border-radius: 10px;
            border: 0.25rem solid black;
            width: 300px;
            margin: 50px auto;
            text-align: center;
        }
        .login_form input {
            width: 100%;
            padding: 0.5rem;
            margin: 0.5rem 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login_button {
            background-color: #003800;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 1rem auto; /* center horizontally */
            display: block;     /* make it take up full line, so margin auto works */
        }

        .error_message {
            color: red;
            margin: 1rem 0;
        }

        h2{
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <?php 
            $page_title = "Login Page";
            include 'header.inc';
        ?>
    </header>

    <main>
        <div class="login_container">
            <h2>Login here!</h2>
            <?php if (isset($error)): ?>
                <p class="error_message"><?php echo $error; ?></p>
            <?php endif; ?>
            <form class="login_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div>
                    <input type="text" name="username" placeholder="Username" required 
                        <?php echo isAccountLocked() ? 'disabled' : ''; ?>>
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required 
                        <?php echo isAccountLocked() ? 'disabled' : ''; ?>>
                </div>
                <button type="submit" class="login_button" <?php echo isAccountLocked() ? 'disabled' : ''; ?>>
                    Login
                </button>
            </form>
        </div>
        <h2>New User? Register your credentials here!</h2>
        <input type="submit" value="User Register" class="login_button" onclick="window.location.href='register_user.php'">
        <input type="submit" value="Manager Register" class="login_button" onclick="window.location.href='register_manager.php'">
    </main>
</body>
</html>
