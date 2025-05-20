<!--Henry's page-->
<!--Started 29/03/2025-->
<?php
session_start(); // Start fresh session
require_once 'settings.php'; // Ensure this file correctly initializes $conn
?>

<!DOCTYPE html>
<html lang="en">
<!-- The header of the webpage. Contains the meta tags and Title -->
<head>
    <!-- Metadata tags -->
     <!--Charset I don't understand-->
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 1 about.html Page (About the team)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML5, Group Project, Home Page, OpenSOS">
    <meta name="author" content="Henry Low">
    <title>About Us</title>

    <link rel="stylesheet" href="styles/style.css">

    <!-- Adds the OpenSOS Icon to title bar -->
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">

</head>

<body>
    <header>
        <?php
            $page_title = "About Us"; // Set the page title
            include 'header.inc'; // Include the header file
        ?>
        <?php include 'nav.inc' ?> <!--Include Navigation Bar-->
    </header>

    <main>
        <!--individual photo and their epic title-->
        <br>
        <table class="team_intro">
          <tr class="team_names">
            <th>Rodney Liaw</th>
            <th>Mark Richards</th>
            <th>Ryan Weber</th>
            <th>Heng Hong Low</th>
          </tr>
          <tr class="team_photos">
            <td><img src="images/Rodney_Liaw.png" alt="Photo of Rodney Liaw"></td>
            <td><img src="images/Mark_Richards.jpg" alt="Photo of Mark Richards"></td>
            <td><img src="images/Ryan_Weber.png" alt="Photo of Ryan Weber"></td>
            <td><img src="images/Henry_Low.png" alt="Photo of Henry Low"></td>
          </tr>
        </table>
      
        <br>
        <!--Who are OpenSOS banner span-->
        <section class="section_span">
          <h2>Who are <span id="highlight">OpenSOS?</span></h2>
        </section>
      
        <!-- FLEX CONTAINER STARTS HERE -->
        <div class="about_content">
          <!-- Group Info -->
          <section class="about_section">
            <h2 class="section_heading">Group Info</h2>
            <ol>
              <li><strong>Group Name:</strong>
                <ul><li>OpenSOS</li></ul>
              </li>
              <li><strong>Class Day:</strong>
                <ul><li>Thursday</li></ul>
              </li>
              <li><strong>Class Time:</strong>
                <ul><li>12:30 - 2:30 PM</li></ul>
              </li>
            </ol>
          </section>
      
            <!-- Member Contributions -->
          <section class="about_section">
            <h2 class="section_heading">Contributions</h2>
            <dl>
              <dt><strong>Rodney Liaw</strong></dt><dd>Index, website design and assistance with other members</dd>
              <dt><strong>Mark Richards</strong></dt><dd>Application, website design and Jira managing</dd>
              <dt><strong>Henry Low</strong></dt><dd>About Us page, design and collaborations</dd>
              <dt><strong>Ryan Weber</strong></dt><dd>Job descriptions and communications</dd>
            </dl>
          </section>
      
          <!-- Student IDs -->
          <section class="about_section">
            <h2 class="section_heading">Student IDs</h2>
            <ul>
              <li>102770761</li>
              <li>102153870</li>
              <li>106010911</li>
              <li>105914892</li>
            </ul>
          </section>
        </div> 

        <!-- Member Interests -->
        <div class="member_interests">
          <table>
              <caption>Our Fabulous Interests</caption>
              <thead>
                  <tr>
                      <th>Student ID</th>
                      <th>Name</th>
                      <th>Interests</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td rowspan="2">102770761</td>
                      <td rowspan="2"><strong>Rodney</strong></td>
                      <td>Reading, Cosplay weapon making</td>
                  </tr>
                  <tr>
                      <td>Traveling, Exploration</td>
                  </tr>

                  <tr>
                      <td rowspan="2">102153870</td>
                      <td rowspan="2"><strong>Mark</strong></td>
                      <td>Coding, Robot building</td>
                  </tr>
                  <tr>
                      <td>Gaming, exercise</td>
                  </tr>
        
                  <tr>
                      <td rowspan="2">106010911</td>
                      <td rowspan="2"><strong>Henry</strong></td>
                      <td>Music and Pokemon</td>
                  </tr>
                  <tr>
                      <td>Movies and Adventures</td>
                  </tr>
        
                  <tr>
                      <td rowspan="2">105914892</td>
                      <td rowspan="2"><strong>Ryan</strong></td>
                      <td>Music, Programming</td>
                  </tr>
                  <tr>
                      <td>Movies, Video game making</td>
                  </tr>
                </tbody>
            </table>
          </div>
          <br><br>

          <div id="about_the_team_container"> <!--Container to allow for flexible resizing at bottom-->
            <section id="about_the_team">
                <h2 class="section_heading">About the Team</h2>
                <p>
                    <strong>We are a faction who harbours the incentive to create innovative and eye catching websites.</strong><br><br>
                    Multiculture and curiousity brought us together to tackle the challenge of creating this grand design.<br><br>
                </p>
            </section>
            <figure>
              <img src="images/COS10026_Group_Photo.png" alt="Group Photo" id="group_photo">
            </figure>
        </div>
      </main>

    <?php include 'footer.inc' ?> <!--Include Footer-->


</body>

</html>
