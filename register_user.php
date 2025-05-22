<!--USER REGISTRATION PAGE-->
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
        $postcode,
        $state,
        $phone
    );

    if ($stmt->execute()) {
        $_SESSION['success_user_register'] = "User registered successfully! You may now log in.";
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
    <meta name="description" content="Project Part 1 register_user.php Page">
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

            <input type="email" id="email" name="email" placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">

            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="retype_password" name="retype_password" placeholder="Retype Password" required>

            <input type="text" id="username" name="username" placeholder="Username" required>

            <!-- Optional details to simplify user applications -->
            <h3>Optional (Make your application easier)</h3>
            <br>

            <fieldset id="applicant_details_registration">
                <section id="personal_details_registration" class="input">
                    <h4>Personal Details:</h4>
                    <br>
                    <!-- Max 20 alpha characters -->
                    <label for="given_name_registration">Given Name: <input type="text" id="given_name_registration" name="given_name_registration" pattern="[a-zA-Z]{1,20}"></label>
                    <br>
                    <!-- Max 20 alpha characters -->
                    <label for="family_name_registration">Family Name: <input type="text" id="family_name_registration" name="family_name_registration" pattern="[a-zA-Z]{1,20}"></label>
                    <br>
                    <label for="dob_registration">Date of Birth: <input type="date" id="dob_registration" name="dob_registration" max="2025-03-27"></label>
                    <br>
                    <fieldset id="gender_selection_registration">
                        <legend>Gender:</legend>
                        <label><input type="radio" name="gender_registration" value="Female"> Female</label>
                        <label><input type="radio" name="gender_registration" value="Male"> Male</label>
                        <label><input type="radio" name="gender_registration" value="Other"> Other</label>
                        <label><input type="radio" name="gender_registration" value="Prefer Not To Say"> Prefer Not To Say</label>
                    </fieldset>
                    <br>
                </section>

                <!-- Get the address details -->
                <section id="address_details_registration" class="input">
                    <h4>Address:</h4>
                    <br>
                    <!-- Max 40 alphanumeric characters for address, allows slashes (/) and dashes (-) as well -->
                    <label for="address_registration">Address: <input type="text" id="address_registration" name="address_registration" placeholder="1/110 John street" pattern="[\da-zA-Z/]{1,40}"></label>
                    <br>
                    <!-- Max 40 characters for address, only allows characters -->
                    <label for="suburb_registration">Suburb: <input type="text" id="suburb_registration" name="suburb_registration" pattern="[a-zA-Z]{1,40}"></label>
                    <br>
                    <!-- 4 digits -->
                    <label for="postcode_registration">Postcode: <input type="text" id="postcode_registration" name="postcode_registration" pattern="\d{4}"></label>
                    <br>
                    <!-- Dropdown menu for the state/territory -->
                    <label for="state_registration">State/Territory
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
                </section>
                <br>

                <!-- Get the applicants contact details -->
                <section id="contact_details_registration" class="input">
                    <label for="phone_number_registration">Phone Number:
                        <!-- Input validation is for 8 to 12 digits, or spaces -->
                        <input type="text" id="phone_number_registration" name="phone_number_registration" placeholder="03 1234 5678" pattern="[0-9 ]{8,12}">
                    </label>
                </section>
                <br>
            </fieldset>

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