<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/threads.css">
        </head>
            <?php include('header.php'); ?>
    <main>
        <h1 class = 'title'>Threads</h1>
        <?php
            if(isset($_SESSION['username'])){
                echo ("<a class='button' href='createThread.php'>Create a thread</a>");
            } else{
                echo ("<p class = 'msg'> You must be loggeed in to create a thread </p>");
            }
            $pageNumber = 0;
            if(isset($_GET['page'])){
                $pageNumber = $_GET['page'];
            }
            include("dbConnection.php");
            $stmt = $conn->prepare("SELECT * FROM threads ORDER BY lastPost DESC LIMIT 10 OFFSET ?");
            $offset = $pageNumber * 10;
            $stmt->bind_param("d", $offset);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while($thread = $result->fetch_assoc()){
                    $id = $thread['threadId'];
                    $title = $thread['threadTitle'];
                    $author = $thread['threadAuthor'];
                    $text = $thread['threadText'];
                    echo "<div class='thread'>"; // Start of block
                    echo("<a href='thread.php?id=" . $thread['threadId'] . "'>");
                    echo "<h2 >{$title}</h2></a>"; // Thread title in bold
                    echo "<p class='author' >Author: {$author}</p>"; // Author underneath
                    echo "<p>{$text}</p>"; // Preview of thread text
                    echo "</div>"; 
                }
            }else {
                echo "Error: " . $stmt->error;
            }
            $conn->close();

        ?>
        <!-- next and back buttons -->
        <?php
        if($pageNumber > 0){
            echo("<a href='threads.php?page=".$pageNumber - 1 ."' class='np'>Previous</a>");
        }
        echo("<a href='threads.php?page=".$pageNumber + 1 ."' class='np'>Next</a>");
        ?>
       
    </main>
</html>