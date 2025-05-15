
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
        <?php
        $link = 'enhancements.php'; // Default fallback

        if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] == 'manager') {
                $link = 'manage.php';
            } elseif ($_SESSION['user_type'] == 'user') {
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
