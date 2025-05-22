<?php
session_start(); // Start fresh session
require_once 'settings.php'; // Ensure this file correctly initializes $conn
?>

<!DOCTYPE html>
<html lang="en">

<!-- The header of the webpage. Contains the meta tags and Title -->
<head>
    <!-- Metadata tags -->
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 1 jobs.html Page">
    <meta name="keywords" content="HTML5, Forms, Group Project, Job Application">
    <meta name="author" content="Ryan Weber">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Job Descriptions</title>

    <link rel = "stylesheet" href = "styles/style.css">
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>

<body>
    <header>
        <?php
            $page_title = "Job Descriptions"; // Set the page title
            include 'header.inc'; // Include the header file
        ?>
        <?php include 'nav.inc' ?> <!--Include Navigation Bar-->
        <br>
    </header>

    <!-- Begin the main content of the web page -->
    <main>
        <!--Job aside and prompt container-->
        <div class = "job_intro">
            <p id = "job_prompt">Here, you can view our amazing job descriptions!<br>Browse and view each one for its required skills, and an overview of the work you will be undertaking!<br>Also see appropriate annual salaries, and other important information.</p>
            <aside id = "job_aside">Use the <a href="apply.php" title="Apply">Apply</a> page to apply for a job.<br>You will need to use the reference code shown in the description of the job you would like to apply for.</aside><!--This might not need to be a float-->
        <div class ="clear_float"></div> <!--clears the float of the aside element so that job descriptions are not affected-->
        </div>

        <!--each job has a division-->
        <!--gets content from database-->
        <?php
            if($db_conn)
            {
                //query jobs database
                $query = "SELECT * FROM jobs";
                $result = mysqli_query($db_conn, $query);

                // Make sure the query returned something, otherwise display an error message
                if ($result)
                {
                    // While the query is returing data from the DB
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        echo '<div class = "job_description_box">';

                        //left
                        echo '<section class = "job_description_left">';
                        echo '<h1 class = "job_title">' . $row['job_title'] . '</h1>';
                        echo '<br><p>' . $row['description'] . '</p><br>';                 

                        //key responsibilities list               
                        echo '<h3>Key Responsibilities</h3>';
                        echo "<ol>";
                        $key_resps = explode("\n", $row['key_responsibilities']); //create array from database text
                        foreach($key_resps as $resp)
                        {
                            echo '<li class = "list_indent">' . $resp . '</li>';
                        }
                        echo "</ol>";

                        echo '</section>';

                        //right
                        echo '<section class = "job_description_right">';
                        echo '<br>';
                        echo '<h3>Key Attributes</h3>';         

                        //essential list
                        echo '<ul><li class = "list_indent">Essential<ul>';
                        $key_attr_esse = explode("\n", $row['key_attributes_essential']); //create array from database text
                        foreach($key_attr_esse as $attr_esse)
                        {
                            echo '<li class = "list_indent">' . $attr_esse . '</li>';
                        }
                        //end essential list, start prefered list
                        echo '</ul></li><li class = "list_indent">Preferred<ul>';
                        $key_attr_pref = explode("\n", $row['key_attributes_preferred']); //create array from database text
                        foreach($key_attr_pref as $attr_pref)
                        {
                            echo '<li class = "list_indent">' . $attr_pref . '</li>';
                        }
                        echo '</ul></li></ul>'; //close master list

                        //extra info
                        echo '<br><br>';
                        echo '<p>Salary: $' . $row['salary_min'] . ' - $' . $row['salary_max'] . ' per annum</p>';
                        echo '<p>Reports to: ' . $row['reports_to'] . '</p/>';
                        echo '<br><br>';
                        echo '<div class="apply_container">';
                        echo '<p class="reference_number"><em>Reference Number: ' . $row['reference_code'] . '</em></p>';

                        echo '<a href="apply.php" class="buttons_description_box">Apply</a></div></section></div>';
                    }
                    echo "<br>";
                }
                else
                {
                    echo "There are no jobs to display.";
                }   
            }
            else
            {
                //didn't work
                echo "No Connection";
            }
        ?>
    </main>
    <?php include 'footer.inc' ?> <!--Include Footer-->
</body>
</html>