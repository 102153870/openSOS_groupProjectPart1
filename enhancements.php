<?php
session_start();

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

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if account is locked
    $lockout_time = isAccountLocked();
    if ($lockout_time > 0) {
        $error = "Account is locked. Please try again in " . ceil($lockout_time / 60) . " minutes.";
    } else {
        // Hardcoded credentials (replace with database check in production)
        if ($username === "admin" && $password === "password123") {
            $_SESSION['username'] = $username;
            $_SESSION['login_attempts'] = 0;
            header("Location: manage.php");
            exit();
        } else {
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
        .login-container {
            background-color: #b8ebab;
            padding: 2rem;
            border-radius: 10px;
            border: 0.25rem solid black;
            width: 300px;
            margin: 50px auto;
            text-align: center;
        }
        .login-form input {
            width: 100%;
            padding: 0.5rem;
            margin: 0.5rem 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-button {
            background-color: #003800;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 1rem;
        }
        .error-message {
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
            $page_title = "Manager Login";
            include 'header.inc'; // Keep if this file exists, or remove this line
        ?>
    </header>

    <main>
        <div class="login-container">
            <h2>Manager Login</h2>
            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <form class="login-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div>
                    <input type="text" name="username" placeholder="Username" required 
                        <?php echo isAccountLocked() ? 'disabled' : ''; ?>>
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required 
                        <?php echo isAccountLocked() ? 'disabled' : ''; ?>>
                </div>
                <button type="submit" class="login-button" <?php echo isAccountLocked() ? 'disabled' : ''; ?>>
                    Login
                </button>
            </form>
        </div>
        <h2>Not a manager? Register your credentials here!</h2>
    </main>
</body>
</html>
