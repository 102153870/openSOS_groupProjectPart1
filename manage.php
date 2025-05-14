<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadata tags -->
     <!--Charset I don't understand-->
    <meta charset="UTF-8">
    <meta name="description" content="Manager screen to view and edit EOI">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML5, Group Project, Home Page, OpenSOS">
    <meta name="author" content="Henry Low">
    <title>About Us</title>

    <link rel="stylesheet" href="styles/style.css">

    <!-- Adds the OpenSOS Icon to title bar -->
    <link rel="icon" type="image/x-icon" href="images/tab_icon.png">

</head>
<body>

    <!-- Database Viewer (Anchored Left, Halfway Across, Full Height) -->
    <fieldset id="databaseViewer">
        <div>
            <p>Placeholder for viewing the database</p>
        </div>
    </fieldset>

    <!-- Search Bar Fieldset -->
    <fieldset id="searchBar">
        <legend>Anything Specific?</legend>
        <input type="text" id="searchInput" name="searchInput">
    </fieldset>

    <!-- Database Editing Fieldset -->
    <fieldset id="databaseEditor">
        <legend>Edit Database</legend>
        <label><input type="radio" name="editOption"> Add to existing file</label><br>
        <label><input type="radio" name="editOption"> Edit existing files</label><br>
        <label><input type="radio" name="editOption"> Delete existing file</label>
    </fieldset>

</body>

</html>