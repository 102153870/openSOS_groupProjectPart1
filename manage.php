<?php
require_once 'settings.php'; // Include database connection
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
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
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 2 manage.php Page">
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
    include 'header.inc';
    include 'nav.inc'; ?>

    <h2 class ="heading_important" id="manager_page_h2">Welcome to the manager dashboard <?php echo htmlspecialchars($username) ?>!</h2>

    <!-- Form to select the query action -->
    <form method="post" class="login_container" id="manager_eoi_form">
        <section id="manager_list_all_and_sort">
            <section id="manager_list_all_eois">
                <h3>List All EOIs</h3>
                <button name="action" value="list_all" class="manager_page_button">List All</button>
            </section>
            <section id="sort_dropdown">
                <!-- Variable used to sort the table info -->
                <?php $query_search_addon = ""; ?>
                <h3>Sort by:</h3>
                <select name="manager_sort_by">
                    <option value="eoi_number">EOI ID</option>
                    <option value="job_ref_number">Job Reference</option>
                    <option value="first_name">First Name</option>
                    <option value="last_name">Last Name</option>
                    <option value="status">Status</option>
                </select>
                <button name="action" value="manager_sort_by" class="manager_page_button">Sort</button>
            </section>
        </section>

        <section id="manager_search_section">
            <section id="manager_search_by_job_ref" class="manager_search_and_delete_subsections">
                <h3>Search by Reference</h3>
                <select name="search_job_ref_number">
                    <option value="" selected="selected">Please Select</option>
                    <!-- Print the jobs dynamically using the DB information -->
                    <?php
                            // Get the job reference numbers from the database and display them in the dropdown box
                            $query = "SELECT * from jobs";
                            $result = mysqli_query($db_conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) 
                            {
                                echo '<option value="' . htmlspecialchars($row['reference_code']) . '">' . htmlspecialchars($row['reference_code']) . ' (' . htmlspecialchars($row['job_title']) . ')</option>';
                            }
                    ?>
                </select>
                <button name="action" value="search_job_ref_number" class="manager_page_button">Search</button>
            </section>
            <section id="manager_search_by_name" class="manager_search_and_delete_subsections">
                <h3>Search by Applicant</h3>
                <label class="manager_name_labels">First Name: <input type="text" name="first_name" placeholder="First Name" class="manager_top_text_input"></label>
                <label class="manager_name_labels">Last Name: <input type="text" name="last_name" placeholder="Last Name"  class="manager_top_text_input"></label>
                <button name="action" value="applicant" class="manager_page_button">Search</button>
            </section>
            <section id="manager_search_by_status" class="manager_search_and_delete_subsections">
            <h3>Search by Status</h3>
            <select name="search_by_status">
                <option value="" selected="selected">Please Select</option>
                <option value="NEW">NEW</option>
                <option value="CURRENT">CURRENT</option>
                <option value="FINAL">FINAL</option>
            </select>
            <button name="action" value="search_by_status" class="manager_page_button">Search</button>
            </section>
        </section>
        
        <section id="manager_delete_section">
            <section id="manager_delete_by_job_ref" class="manager_search_and_delete_subsections">
                <h3>Delete by Reference</h3>
                <select name="delete_job_ref_number">
                    <option value="" selected="selected">Please Select</option>
                    <!-- Print the jobs dynamically using the DB information -->
                    <?php
                            // Get the job reference numbers from the database and display them in the dropdown box
                            $query = "SELECT * from jobs";
                            $result = mysqli_query($db_conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) 
                            {
                                echo '<option value="' . htmlspecialchars($row['reference_code']) . '">' . htmlspecialchars($row['reference_code']) . ' (' . htmlspecialchars($row['job_title']) . ')</option>';
                            }
                    ?>
                </select>
                <button name="action" value="delete_job_ref_number" class="manager_page_button">Delete</button>
            </section>
            <section id="manager_delete_by_name" class="manager_search_and_delete_subsections">
                <h3>Delete by Applicant</h3>
                <label class="manager_name_labels">First Name: <input type="text" name="delete_first_name" placeholder="First Name" class="manager_top_text_input"></label>
                <label class="manager_name_labels">Last Name: <input type="text" name="delete_last_name" placeholder="Last Name"  class="manager_top_text_input"></label>
                <button name="action" value="delete_applicant" class="manager_page_button">Delete</button>
            </section>
            <section id="manager_delete_by_status" class="manager_search_and_delete_subsections">
                <h3>Delete by Status</h3>
                <select name="delete_by_status">
                    <option value="" selected="selected">Please Select</option>
                    <option value="NEW">NEW</option>
                    <option value="CURRENT">CURRENT</option>
                    <option value="FINAL">FINAL</option>
                </select>
                <button name="action" value="delete_by_status" class="manager_page_button">Delete</button>
            </section>
        </section>
    </form>

<?php
// Handle different query types
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $action = $_POST['action'];

    /* List all EOIs */
    if ($action == "list_all") 
    {
        $query = "SELECT * FROM eoi";

        $_SESSION['last_query'] = $query;
    } 
    /* Search by job reference */
    elseif ($action == "search_job_ref_number") 
    {
        if ($_POST['search_job_ref_number'] != "")
        {
            $job_ref_number = mysqli_real_escape_string($db_conn, $_POST['search_job_ref_number']);
            $query = "SELECT * FROM eoi WHERE job_ref_number = '$job_ref_number'";
        }
        else $query = " ";

        $_SESSION['last_query'] = $query;
    } 
    /* Search by applicant name (allows for both first and last name or both) */
    elseif ($action == "applicant") 
    {
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
        else $query = " ";

        $_SESSION['last_query'] = $query;
    } 
    /* Search by status */
    elseif ($action == "search_by_status") 
    {
        if ($_POST['search_by_status'] != "")
        {
            $status = mysqli_real_escape_string($db_conn, $_POST['search_by_status']);
            $query = "SELECT * FROM eoi WHERE status = '$status'";
        }
        else $query = " ";

        $_SESSION['last_query'] = $query;
    } 
    /* Delete by job reference */
    elseif ($action == "delete_job_ref_number") 
    {
        if ($_POST['delete_job_ref_number'] != "")
        {
            $job_ref_number = mysqli_real_escape_string($db_conn, $_POST['delete_job_ref_number']);
            $query = "DELETE FROM eoi WHERE job_ref_number = '$job_ref_number'";
            mysqli_query($db_conn, $query);
            echo "<p class = \"heading_important\">Deleted EOIs for job reference: $job_ref_number</p>";

            // Refresh the table after update
            $query = "SELECT * FROM eoi";
        }
        else $query = " ";

        $_SESSION['last_query'] = $query;
    } 
    /* Delete applications by status */
    elseif ($action == "delete_by_status") 
    {
        if ($_POST['delete_by_status'] != "")
        {
            $status = mysqli_real_escape_string($db_conn, $_POST['delete_by_status']);
            $query = "DELETE FROM eoi WHERE status = '$status'";
            mysqli_query($db_conn, $query);
            echo "<p class = \"heading_important\">Deleted EOIs for status: $status</p>";

            // Refresh the table after update
            $query = "SELECT * FROM eoi";
        }
        else $query = " ";

        $_SESSION['last_query'] = $query;
    } 
    /* Delete applications by applicant name */
    elseif ($action == "delete_applicant") 
    {
        $conditions = [];
        if (!empty($_POST['delete_first_name'])) { //Check if first name is not empty
            $fname = mysqli_real_escape_string($db_conn, $_POST['delete_first_name']); //Escape special characters
            $conditions[] = "first_name LIKE '%$fname%'"; //Make an array of conditions to check for first and last name pairing
        }
        if (!empty($_POST['delete_last_name'])) {
            $lname = mysqli_real_escape_string($db_conn, $_POST['delete_last_name']);
            $conditions[] = "last_name LIKE '%$lname%'";
        }
        if (count($conditions) > 0) 
        {
            $query = "DELETE FROM eoi WHERE " . implode(" AND ", $conditions);
            mysqli_query($db_conn, $query);
            
            // Echo the names that were provided
            $nameString = "";
            if (!empty($_POST['delete_first_name'])) $nameString .= $_POST['delete_first_name'];
            if (!empty($_POST['delete_last_name'])) 
            {
                if ($nameString) $nameString .= " ";
                $nameString .= $_POST['delete_last_name'];
            }
            echo "<p class = \"heading_important\">Deleted EOIs for: " . htmlspecialchars($nameString) . "</p>";

            // Refresh the table after update
            $query = "SELECT * FROM eoi";
        }
        else $query = " ";

        $_SESSION['last_query'] = $query;
    } 
    /* Update status */
    elseif ($action == "update_status" && !empty($_POST['eoi_number']) && !empty($_POST['status'])) 
    {
        if ($_POST['status'] != "")
        {
            $id = intval($_POST['eoi_number']);
            $status = mysqli_real_escape_string($db_conn, $_POST['status']);
            $query = "UPDATE eoi SET status = '$status' WHERE eoi_number = $id";
            mysqli_query($db_conn, $query);
            echo "<p class =\"heading_important\">Updated status of EOI ID $id to '$status'</p>";
            
            // Refresh the table after update
            $query = "SELECT * FROM eoi";
        }
        else $query = " ";

        $_SESSION['last_query'] = $query;
    }

    if ($action == "manager_sort_by")
    {
        if($_POST['manager_sort_by'] != "")
        {
            $query = $_SESSION['last_query'] . " ORDER BY " . $_POST['manager_sort_by'];
        }
        else $query = " ";
    }

    //Display the results in a table
    if (isset($query)) 
    {
        $result = mysqli_query($db_conn, $query);
        if ($result && mysqli_num_rows($result) > 0) 
        { //Check if there are results in the eoi table
            echo "<table border='1'><tr><th>ID</th><th>Job Ref</th><th>Name</th><th>DOB</th><th>Gender</th>
            <th>Address</th><th>Suburb</th><th>State</th><th>Postcode</th><th>Email Address</th><th>Phone Number</th>
            <th>Skills</th><th>Other Skills</th><th>Status</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) 
            {
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
                    <td id='status_color'>
                        <form method='post'>
                            <input type='hidden' name='eoi_number' value='" . $row['eoi_number'] . "'>
                            <select name='status'> <!-- Dropdown for status selection, using ternary operators -->
                                <option value='NEW'" . ($row['status'] == 'NEW' ? ' selected' : '') . ">NEW</option>
                                <option value='CURRENT'" . ($row['status'] == 'CURRENT' ? ' selected' : '') . ">CURRENT</option>
                                <option value='FINAL'" . ($row['status'] == 'FINAL' ? ' selected' : '') . ">FINAL</option>
                            </select>
                            <button type='submit' name='action' value='update_status'>Update</button>
                        </form>
                    </td>
                    </tr>";
            }
            echo "</table>";
        } 
        else echo "<p id='manage_message'>No records found or query failed.</p>";
    }
}
?>

<br>
<!-- Logout button -->
<form method="post" class="profile_container">
    <button type="submit" name="logout" class="buttons">Logout</button>
</form>

</body>
</html>
