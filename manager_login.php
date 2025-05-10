<?php
require_once 'login_session.php';

// Check login credentials (replace with database validation in production)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Check if account is locked
    $lockout_time = isAccountLocked();
    if ($lockout_time > 0) {
        $error = "Account is locked. Please try again in " . ceil($lockout_time/60) . " minutes.";
    } else {
        // Verify credentials (replace with proper validation)
        if ($username === "admin" && $password === "password123") {
            $_SESSION['login_attempts'] = 0;
            header("Location: manager_dashboard.php");
            exit();
        } else {
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] >= 3) {
                $_SESSION['lockout_time'] = time();
                $error = "Account locked. Please try again in 5 minutes.";
            } else {
                $error = "Invalid credentials. " . (3 - $_SESSION['login_attempts']) . " attempts remaining.";
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
    </style>
</head>
<body>
    <header>
        <?php 
            $page_title = "Manager Login";
            include 'header.inc';
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
    </main>

    <?php include 'footer.inc'; ?>
</body>
</html>