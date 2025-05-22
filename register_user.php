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
    $password = sanitise_input($_POST['password']);

    //using filter_var to validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_user_register'] = "Invalid email format."; //Use session to persist error message after redirect
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

    // Insert into users table (using prepared statement)
    $insert_query = "INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, 'user')";
    $stmt = $db_conn->prepare($insert_query);
    $stmt->bind_param("sss", $email, $username, $hashed_password);

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

            <label for="username" class="visually_hidden">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username" required>

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