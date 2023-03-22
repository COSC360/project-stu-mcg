<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/profile.css">

        </head>
        <?php include('header.php'); ?>
        <main>
            <div class="mainDiv">
                <h1>User1234</h1>
                <img class="profilePic" src="img/blank-profile-picture-973460_1280.png">
                <h2>Bio:</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ante turpis, molestie in orci eu, aliquam sagittis augue. Pellentesque tincidunt.</p>
                <a id = "editLink" href="#">edit profile<a>
                <h2>Location: Kelowna BC</h2>
                <h2>Post Count: 3333</h2>
                <h2>Recent threads:</h2>
                <ul>
                    <li><a href="#">Thread 1</a></li>
                    <li><a href="#">Thread 2</a></li>
                    <li><a href="#">Thread 3</a></li>
                    <li><a href="#">Thread 4</a></li>
                    <li><a href="#">Thread 5</a></li>
                </ul>
                <?php
                    if(isset($_POST['logout'])){
                        unset($_SESSION['username']);
                        header("Location: login.php");
                        exit();
                    }
                ?>
                <form method="post">
                    <button type="submit" class="logout" name="logout">Logout</button>
                </form>
            </div>
        </main>
    </body>
</html>