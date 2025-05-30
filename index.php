<!--HOME PAGE-->
<!--In-charge: Rodney (Leader)-->
<?php
session_start(); // Start fresh session
require_once 'settings.php'; // Ensure this file correctly initializes $conn
                        
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Project Part 1 index.php Page (Home Page)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML5, Group Project, Home Page, OpenSOS">
    <meta name="author" content="Rodney Liaw">
    <title>OpenSOS</title>

    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">
</head>


<body class="hero_background">
    <!--Group Name and Top of page/Manager Login -->
    <header class="home_header">
        <div class="theme_container">
            <!-- Placeholder for the left item -->
            <?php include 'theme.inc' ?> <!--Include Theme Switcher-->
        </div>
        <div class="page_title_container">
            <a href="index.php">
                <img src="images/OpenSOS_logo_nobg.png" alt="OpenSOS logo" id="header_logo">
            </a>
        </div>
    <!-- Right: Manager Login -->
    <div class="header_login_link">
        <?php
        $link = 'login.php'; // Default fallback

        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'manager') {
                $link = 'manage.php';
            } elseif ($_SESSION['role'] == 'user') {
                $link = 'profile.php';
            }
        }
        ?>
        <a href="<?php echo $link; ?>">
            <img src="images/manager_login_icon.png" alt="Manager Login Icon" id="manager_logo">
            <p>
                <?php
                    if (isset($_SESSION['username'])) {
                        echo 'Welcome, ' . htmlspecialchars($_SESSION['username']);
                    } else {
                        echo 'User Login';
                    }
                ?>
            </p>
        </a>
    </div>
    </header>

    <!--Main content for page-->
    <main>
        <!--Description of Company-->
        <section class="company_description">
            <h2 class ="heading_important">What We Do</h2>
            <p><strong>OpenSOS</strong> is a forward-thinking programming company dedicated to delivering top-quality solutions across various fields of computer science. 
                Our expertise spans software development, data analysis, artificial intelligence, cybersecurity, and IT consulting, ensuring that businesses 
                stay ahead in the digital landscape. With a team of highly skilled professionals, we leverage cutting-edge technology to develop efficient, 
                scalable, and innovative solutions tailored to our client&apos;s unique needs. Whether it&apos;s optimizing business processes through data insights or 
                building robust software applications, we are committed to excellence in every project. At OpenSOS, we take pride in "sourcing the best" talent,
                 technology, and strategies to drive success for our clients.</p>
                 <!--Generated with ChatGPT, prompt: Give me a description for a hiring IT company names OpensSOS-->
        </section>


        <!--Navigation Menu-->
        <section class="home_nav">
            <div class="section_span">
                <h2>Think you have what it takes? <br> Apply now and check out more information!</h2>
            </div>
            <br>

            <nav>
                <div class="nav_item"><!--Careers Nav Icon-->
                    <a href="jobs.php">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g> 
                                <path d="M9 7H5C3.89543 7 3 7.89543 3 9V18C3 19.1046 3.89543 20 5 20H19C20.1046 20 21 19.1046 21 18V9C21 7.89543 20.1046 7 19 7H15M9 7V5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7M9 7H15" 
                                stroke="#000000" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"></path> 
                            </g>
                            <!--SVG Source: https://www.svgrepo.com/svg/489230/work-alt-->    
                        </svg>
                        <p><b>Careers</b></p>  
                    </a>
                </div>
            
                <div class="nav_item"><!--Apply Nav Icon-->
                    <a href="apply.php">
                        <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none">
                            <g> 
                                <path d="M10 3a7 7 0 100 14 7 7 0 000-14zm-9 7a9 9 0 1118 0 9 9 0 01-18 0zm14 .069a1 1 0 01-1 1h-2.931V14a1 1 0 11-2 0v-2.931H6a1 1 0 110-2h3.069V6a1 
                                1 0 112 0v3.069H14a1 1 0 011 1z" stroke="#000000" stroke-width="0.4" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                            <!--SVG Source: https://www.svgrepo.com/svg/509388/plus-circle-->  
                        </svg>
                        <p><b>Apply</b></p>
                    </a>
                </div>

                <div class="nav_item"> <!--About Us Nav Icon-->
                    <a href="about.php">
                        <svg viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000">
                            <g transform="translate(42.666667, 42.666667)"> 
                                <path d="M213.333333,3.55271368e-14 C331.154987,3.55271368e-14 426.666667,95.51168 426.666667,213.333333 C426.666667,331.153707 331.154987,426.666667 213.333333,426.666667 C95.51296,426.666667 
                                3.55271368e-14,331.153707 3.55271368e-14,213.333333 C3.55271368e-14,95.51168 95.51296,3.55271368e-14 213.333333,3.55271368e-14 Z M234.713387,192 L192.04672,192 L192.04672,320 L234.713387,320 
                                L234.713387,192 Z M213.55008,101.333333 C197.99616,101.333333 186.713387,112.5536 186.713387,127.704107 C186.713387,143.46752 197.698773,154.666667 213.55008,154.666667 C228.785067,154.666667 
                                240.04672,143.46752 240.04672,128 C240.04672,112.5536 228.785067,101.333333 213.55008,101.333333 Z" id="Shape" stroke="#000000" stroke-width="11" stroke-linecap="round" stroke-linejoin="round"> </path> 
                            </g> 
                            <!--SVG Source: https://www.svgrepo.com/svg/486514/about-filled-->
                        </svg>
                        <p><b>About Us</b></p>    
                    </a>
                </div>
            </nav>
        </section>

        <!--Email-->
        <section class="home_email">
            <h2 class ="heading_important">Company Contact info:</h2>
            <p id="email_prompt">Have any questions, queries or prefer to talk to us directly? Drop us an email and we'll be glad to help you out!</p>
            
            <a href="mailto:info@opensos.com.au">
                <img src="images/email_icon.png" alt="info@opensos.com.au" id="email_icon">
                <!--Image source: https://thenounproject.com/browse/icons/term/letter-icon/-->
                <p><strong>info@openSOS.com.au</strong></p>
            </a>
        </section>

        <!--Enhancement Section-->
        <section class ="section_heading">
            <a class="buttons" href='enhancements.php'">Go to Enhancements</a> 
        </section>
        <br>
        
    </main>

    <?php include 'footer.inc' ?> <!--Include Footer-->
    
</body>

</html>
