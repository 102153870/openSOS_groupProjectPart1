<header class="home_header">
    <!-- Left: Theme Selector -->
    <div class="theme_container">
        <?php include 'theme.inc'; ?>
    </div>

    <!-- Center: Logo + Page Title -->
    <div class="page_title_container">
        <a href="index.php">
            <img src="images/tab_icon.png" alt="OpenSOS logo" id="header_logo_not_homepage">
        </a>
        <h1 id="heading_not_homepage"><?php echo $page_title; ?></h1>
    </div>

    <!-- Right: Manager Login -->
    <div class="manager_login_link">
        <a href="enhancements.php">
            <img src="images/manager_login_icon.png" alt="Manager Login Icon" id="manager_logo">
            <p>User Log In</p>
        </a>
    </div>
</header>
