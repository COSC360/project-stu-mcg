<!DOCTYPE html>
<head>
<link rel="stylesheet" href="css/header.css">
</head>
<?php session_start(); ?> 
<header class="header">
    <a href="threads.php"><img  class = "logo" src="img/logo1.png"></a>
    <ul class="main-nav">
        <?php 
            if(isset($_SESSION['isAdmin'])){
                echo '<li><a href="threads.php">Manage Threads</a></li>';
                echo '<li><a href="admin.php">Manage Users</a></li>';
                echo '<li><a href="stats.php">Analytics</a></li>';
            }
            else{
                echo '<li><a href="threads.php">Threads</a></li>';
            }
        ?>
        <li>
            <?php 
            if(isset($_SESSION['username'])){
                echo '<a href="profile.php">'. $_SESSION['username'] .'</a>';
            } else{
                echo '<a href="login.php">'.'Login</a>';
            }
            ?>
        </li>
    </ul>
</header> 