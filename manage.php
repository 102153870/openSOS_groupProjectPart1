<?php
require_once 'settings.php'; // Include database connection
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: manager_login.php");
    exit();
}

// Get username from session
$username = $_SESSION['username'];

//Check if logout button is pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();         // Unset all session variables
    session_destroy();       // Destroy the session
    header('Location: index.php'); // Redirect to login or another page
    exit;
}

//SECURITY: Check if user is a manager, if not redirect to index.php
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'manager') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 1 manage.php Page">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML5, Group Project, Home Page, OpenSOS">
    <meta name="author" content="Rodney Liaw">
    <title>Manager Dashboard</title>

    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>
<body>
    <?php 
    $page_title = "Dashboard";
    include 'header.inc'; ?>

    <h2>Welcome to the manager dashboard <?php echo htmlspecialchars($username) ?>!</h2>

<?php
// Handle different query types
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    // List all EOIs
    if ($action == "list_all") {
        $query = "SELECT * FROM eoi";

    // Search by job reference
    } elseif ($action == "search_job_ref_number" && !empty($_POST['search_job_ref_number'])) {
        $job_ref_number = mysqli_real_escape_string($db_conn, $_POST['search_job_ref_number']);
        $query = "SELECT * FROM eoi WHERE job_ref_number = '$job_ref_number'";

    // Search by applicant name (allows for both first and last name or both)
    } elseif ($action == "applicant") {
        $conditions = [];
        if (!empty($_POST['first_name'])) { //Check if first name is not empty
            $fname = mysqli_real_escape_string($db_conn, $_POST['first_name']); //Escape special characters
            $conditions[] = "first_name LIKE '%$fname%'"; //Make an array of conditions to check for first and last name pairing
        }
        if (!empty($_POST['last_name'])) {
            $lname = mysqli_real_escape_string($db_conn, $_POST['last_name']);
            $conditions[] = "last_name LIKE '%$lname%'";
        }
        if (count($conditions) > 0) {
            $query = "SELECT * FROM eoi WHERE " . implode(" AND ", $conditions);
        }

    // Delete by job reference
    } elseif ($action == "delete_job_ref_number" && !empty($_POST['delete_job_ref_number'])) {
        $job_ref_number = mysqli_real_escape_string($db_conn, $_POST['delete_job_ref_number']);
        $query = "DELETE FROM eoi WHERE job_ref_number = '$job_ref_number'";
        mysqli_query($db_conn, $query);
        echo "<p>Deleted EOIs for job reference: $job_ref_number</p>";
        $query = null; //Reset the query 

    // Update status
    } elseif ($action == "update_status" && !empty($_POST['eoi_number']) && !empty($_POST['status'])) {
        $id = intval($_POST['eoi_number']);
        $status = mysqli_real_escape_string($db_conn, $_POST['status']);
        $query = "UPDATE eoi SET status = '$status' WHERE eoi_number = $id";
        mysqli_query($db_conn, $query);
        echo "<p>Updated status of EOI ID $id to '$status'</p>";
        $query = null;
    }

    //Display the results in a table
    if (isset($query)) {
        $result = mysqli_query($db_conn, $query);
        if ($result) {
            echo "<table border='1'><tr><th>ID</th><th>Job Ref</th><th>Name</th><th>DOB</th><th>Gender</th>
            <th>Address</th><th>Suburb</th><th>State</th><th>Postcode</th><th>Email Address</th><th>Phone Number</th>
            <th>Skills</th><th>Other Skills</th><th>Status</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>{$row['eoi_number']}</td>
                    <td>{$row['job_ref_number']}</td>
                    <td>{$row['first_name']} {$row['last_name']}</td>
                    <td>{$row['dob']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$row['address']}</td>
                    <td>{$row['suburb']}</td>
                    <td>{$row['state']}</td>
                    <td>{$row['postcode']}</td>
                    <td>{$row['email_address']}</td>
                    <td>{$row['phone_number']}</td>
                    <td>{$row['skills']}</td>
                    <td>{$row['other_skills']}</td>
                    <td>{$row['status']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No records found or query failed.</p>";
        }
    }
}
?>

<!-- Form to select the action -->
<form method="post" class="login_container">
    <h3>List All EOIs</h3>
    <button name="action" value="list_all">List All</button>

    <h3>Search by Job Reference</h3>
    <input type="text" name="search_job_ref_number" placeholder="Job Reference">
    <button name="action" value="search_job_ref_number">Search</button>

    <h3>Search by Applicant</h3>
    <input type="text" name="first_name" placeholder="First Name">
    <input type="text" name="last_name" placeholder="Last Name">
    <button name="action" value="applicant">Search</button>

    <h3>Delete by Job Reference</h3>
    <input type="text" name="delete_job_ref_number" placeholder="Job Reference">
    <button name="action" value="delete_job_ref_number">Delete</button>

    <h3>Update Status</h3>
    <input type="text" name="eoi_number" placeholder="EOI ID">
    <input type="text" name="status" placeholder="New Status">
    <button name="action" value="update_status">Update</button>
</form>

<!-- Logout button -->
<form method="post">
    <button type="submit" name="logout" class="buttons">Logout</button>
</form>

</body>
</html>
