<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/threads.css">
        </head>
            <?php include('header.php'); ?>
    <main>
        <?php
            if(!isset($_GET['id'])){
                die("null thread");
            }
            $threadId = $_GET['id'];
            $pageNumber = 0;
            if(isset($_GET['page'])){
                $pageNumber = $_GET['page'];
            }
            include("dbConnection.php");
            $stmt = $conn->prepare("SELECT * FROM threads WHERE threadId = ?");
            $stmt->bind_param("d", $threadId);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if($thread = $result->fetch_assoc()){
                    echo("<h2>".$thread['threadTitle']."</h2>");
                    echo("<p>By: ".$thread['threadAuthor']."</p>");
                    echo("<p>".$thread['threadText']."</p>");
                }else{
                    die("thread does not exist");
                }
            } else {
                echo "Error: " . $stmt->error;
            }
            $offset = $pageNumber * 10;
            //get thread comments
        ?>
        <!-- next and back buttons -->
        <?php
        if($pageNumber > 0){
            echo("<a href='threads.php?page=".$pageNumber - 1 ."id=" . $threadId ."'>Previous</a>");
        }
        echo("<a href='threads.php?page=".$pageNumber + 1 ."id=" . $threadId ."'>Next</a>");
        ?>
    </main>
</html>