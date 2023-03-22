<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/form.css">
            <script src="scripts/signup-validation.js"></script>
        </head>
            <?php include('header.php'); ?>
    <main>
        <?php
            if(isset($_SESSION['username'])){
                echo ("<a href='createThread.php'>Create a thread</a>");
            } else{
                echo ("you must be loggeed in to create a thread");
            }
        ?>
    </main>
</html>