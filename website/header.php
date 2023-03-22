<!DOCTYPE html>
<head>
<link rel="stylesheet" href="css/header.css">
</head>
<?php session_start(); //since header is included on all pages this the only place i need to start session?> 
<header class="header">
    <a href="#"><img  class = "logo" src="img/logo1.png"></a>
    <ul class="main-nav">
        <li><a href="#">Home</a></li>
        <li><a href="#">Topics</a></li>
        <li><a href="threads.php">Threads</a></li>
        <li>
            <a 
                <?php 
                if(isset($_SESSION['username'])){
                    echo 'href="profile.php">'. $_SESSION['username'];
                } else{
                    echo 'href="login.php">'.'Login';
                }
                ?>
            </a>
        </li>
        <li><input type="text" placeholder="Search"></li>
    </ul>
</header> 