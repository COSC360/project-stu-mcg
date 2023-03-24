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

                <?php 
                    if(isset($_GET['user'])){
                        $username = $_GET['user'];
                    }else if(isset($_SESSION['username'])){
                        $username = $_SESSION['username'];
                    }else{
                        die("null user");
                    }
                    if($username == ($_SESSION['username'])){
                        echo('<a id = "editLink" href="editProfile.php">edit profile</a>');
                    }
                    include('dbConnection.php');
                    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    if($stmt->execute()){
                        $result = $stmt->get_result();
                        if($user = $result->fetch_assoc()){
                            $bio = $user['bio'];
                            $location = $user['location'];
                            if($location == ""){
                                $location = "Unknown";
                            }
                        }else{
                            die("user does not exist");
                        }
                    } else {
                        die("Error: " . $stmt->error);
                    }
                    echo ('<h1>'. $username.'</h1>');
                    if($result = glob("./userImages/" . $username . ".*")){
                        echo("<img class='profilePic' src='{$result[0]}'>");
                    } else {
                        echo("<img class='profilePic' src='img/blank-profile-picture-973460_1280.png'>");
                    }
                ?>
                <h2>Bio:</h2>
                <p><?php echo($bio)?></p>
                <h2>Location: <?php echo($location)?></h2>
                <h2>
                    Post Count:
                    <?php
                        $stmt = $conn->prepare('SELECT (SElECT COUNT(threadId) FROM threads WHERE threadAuthor = ?) AS threadCount, (SELECT COUNT(replyId) FROM replies WHERE replyAuthor = ?) AS replyCount');
                        $stmt->bind_param('ss' , $username, $username);
                        if($stmt->execute()){
                            $result = $stmt->get_result();
                            $value = $result->fetch_assoc();
                            echo($value['threadCount'] + $value['replyCount']);
                        }else{
                            die($stmt->error);
                        }
                    ?>
                </h2>
                <h2>Recent threads:</h2>
                <ul>
                    <?php
                        $stmt = $conn->prepare('SELECT * FROM threads WHERE threadAuthor = ? ORDER BY threadDate DESC LIMIT 5');
                        $stmt->bind_param('s', $username);
                        if($stmt->execute()){
                            $result = $stmt->get_result();
                            while($thread = $result->fetch_assoc()){                            
                                echo("<a href = 'thread.php?id=" . $thread['threadId'] . "'><li>" . $thread['threadTitle'] . "</li></a>");
                            }
                        }else{
                            die($stmt->error);
                        }
                    ?>
                </ul>
                <?php
                    if(isset($_POST['logout'])){
                        unset($_SESSION['username']);
                        if(isset($_SESSION['isAdmin'])){
                            unset($_SESSION['isAdmin']);
                        }
                        if(isset($_SESSION['banned'])){
                            unset($_SESSION['banned']);
                        }
                        header("Location: login.php");
                        exit();
                    }
                ?>
                <form method="post">
                    <button type="submit" class="logout" name="logout">Logout</button>
                </form>
            </div>
        </main>
        <?php $conn->close();?>
    </body>
</html>