<!DOCTYPE html>
<head>
<link rel="stylesheet" href="css/header.css">
</head>
<?php session_start(); //since on every page it's all g?> 
<header class="header">
    <a href="#"><img  class = "logo" src="img/logo1.png"></a>
    <ul class="main-nav">
        <li><a href="#">Home</a></li>
        <li><a href="#">Topics</a></li>
        <li><a href="#">Threads</a></li>
        <li><a href="login.php"><?php if(isset($_SESSION['username'])){echo $_SESSION['username'];} else{echo 'Login';}?></a></li>
        <li><input type="text" placeholder="Search"></li>
    </ul>
</header> 