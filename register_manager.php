<!--MANAGER REGISTRATION PAGE-->
<!--In-charge: Rodney/Henry-->
<?php
session_start();
require_once 'settings.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitise_input($_POST["email"]);
    $username = sanitise_input($_POST["username"]);
    $password = sanitise_input($_POST["password"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_manager_register'] = "Invalid email format.";
        header("Location: register_manager.php");
        exit();
    }

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db_conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_manager_register'] = "Email is already registered.";
        header("Location: register_manager.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insert_query = "INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, 'manager')";
    $stmt = $db_conn->prepare($insert_query);
    $stmt->bind_param("sss", $email, $username, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success_manager_register'] = "Manager registered successfully!";
    } else {
        $_SESSION['error_manager_register'] = "Registration failed. Try again.";
    }

    header("Location: register_manager.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 1 register_manager.php Page">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML5, Group Project, Home Page, OpenSOS">
    <meta name="author" content="Rodney Liaw">
    <title>Manager Registration</title>

    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>

<body>
    <header>
        <?php
            $page_title = "Admin Register";
            include 'header.inc'; // Ensure this path is correct
        ?>
    </header>

    <main>
    <div class="login_container">
        <h2>Register here!</h2>

        <!--Registration form -->
        <form class="login_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php
            if (isset($_SESSION['error_manager_register'])) {
                echo "<p class='error_message'>" . $_SESSION['error_manager_register'] . "</p>";
                unset($_SESSION['error_manager_register']);
            } elseif (isset($_SESSION['success_manager_register'])) {
                echo "<p class='success_message'>" . $_SESSION['success_manager_register'] . "</p>";
                unset($_SESSION['success_manager_register']);
            }
            ?>

            <input type="email" id="email" name="email" placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">

            <input type="text" id="username" name="username" placeholder="Username" required>

            <input type="password" id="password" name="password" placeholder="Password" required>

            <input type="password" id="company_password" name="company_password" placeholder="Company Password" required>

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