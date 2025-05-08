<!DOCTYPE html>
<html lang="en">
<!-- The header of the webpage. Contains the meta tags and Title -->
<head>
    <!-- Metadata tags -->
     <!--Charset I don't understand-->
    <meta charset="UTF-8">
    <meta name="description" content="A page to view and manage all EOI">
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
            $page_title = "Manage"; // Set the page title
            include 'header.inc'; // Include the header file
        ?>
        <?php include 'nav.inc' ?> <!--Include Navigation Bar-->
    </header>
</body>