<!--MANAGER REGISTRATION PAGE-->
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Sanitise all the inputs
    $email = sanitise_input($_POST["email"]);
    $username = sanitise_input($_POST["username"]);
    $password = sanitise_input($_POST["password"]);
    $company_password = sanitise_input($_POST["company_password"]);

    //Checking if the email format is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_manager_register'] = "Invalid email format.";
        header("Location: register_manager.php");
        exit();
    }

    // Check if the company password is correct using prepared statement
    $query = "SELECT company_password FROM manager_password LIMIT 1"; //There is only one company password
    $stmt = $db_conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    //If the company password matches the one in the database
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $correct_password = $row['company_password']; //Store the correct password

        // Verify the company password
        if ($company_password !== $correct_password) { //Company password entered is not correct
            $_SESSION['error_manager_register'] = "Incorrect company password.";
            header("Location: register_manager.php");
            exit();
        }

    } else {
        // Handle query failure or no password set
        $_SESSION['error_manager_register'] = "Password verification error. Please contact support.";
        header("Location: register_manager.php");
        exit();
    }
    $stmt->close();

    //checking if email is already registered
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db_conn->prepare($query); //using prepared statements to prevent SQL injection
    $stmt->bind_param("s", $email); //Bind the email parameter to the query (s - string)
    $stmt->execute();
    $result = $stmt->get_result(); //Execute the query and get the result

    //If the email is already registered
    if ($result->num_rows > 0) {
        $_SESSION['error_manager_register'] = "Email is already registered.";
        header("Location: register_manager.php");
        exit();
    }
    $stmt->close();

    //Hashing the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insert_query = "INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, 'manager')";
    $stmt = $db_conn->prepare($insert_query);
    $stmt->bind_param("sss", $email, $username, $hashed_password);

    //Checking if the password is hashed correctly
    if ($stmt->execute()) {
        $_SESSION['success_manager_register'] = "Manager registered successfully! You may now <a href=\"login.php\">LOGIN</a>.";
        $_SESSION['is_registered'] = true; // Set a session variable to indicate registration success
    } else {
        $_SESSION['error_manager_register'] = "Registration failed. Try again.";
    }
    $stmt->close();

    header("Location: register_manager.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 2 register_manager.php Page">
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
            <?php //Display error or success messages depending on logic set in PHP
            if (isset($_SESSION['error_manager_register'])) {
                echo "<p class='error_message'>" . $_SESSION['error_manager_register'] . "</p>";
                unset($_SESSION['error_manager_register']);
            } elseif (isset($_SESSION['success_manager_register'])) {
                echo "<p class='success_message'>" . $_SESSION['success_manager_register'] . "</p>";
                unset($_SESSION['success_manager_register']);
            }
            ?>

            <!-- Input fields for registration  -->
                <label for="email" class="visually_hidden">Email:</label>
                <input type="email" id="email" name="email" placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">

                <label for="username" class="visually_hidden">Username:</label>
                <input type="text" id="username" name="username" placeholder="Username" required>

                <label for="password" class="visually_hidden">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required>

                <label for="company_password" class="visually_hidden">Company Password:</label>
                <input type="password" id="company_password" name="company_password" placeholder="Company Password" required>


            <!-- Submit button -->
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
