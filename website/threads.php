<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/threads.css">
        </head>
            <?php include('header.php'); ?>
    <main>
        <div class = "search">
        <h1 class = 'title'>Threads</h1>
            <form method="post" action="">
                <input type="text" name="search" placeholder="Search">
                <input type="submit" value="Search">
            </form>
        
        <?php
            if(isset($_SESSION['username']) and !isset($_SESSION['banned'])){
                echo ("<a class='button' href='createThread.php'>Create a thread</a></div>");
            }elseif (isset($_SESSION['username']) and isset($_SESSION['banned'])){
                echo ("<p class = 'msg'> Your account has been suspended from posting </p></div>");
            }else{
                echo ("<p class = 'msg'> You must be logged in to post</p></div>");
            }
            $pageNumber = 0;
            if(isset($_GET['page'])){
                $pageNumber = $_GET['page'];
            }
            include("dbConnection.php");

            $search = "";
            if (isset($_POST['search'])) {
                $search = $_POST['search'];
            }

            $stmt = $conn->prepare("SELECT * FROM threads WHERE threadTitle LIKE '%$search%' OR threadAuthor LIKE '%$search%' ORDER BY lastPost DESC LIMIT 11 OFFSET ?");
            $offset = $pageNumber * 10;
            $stmt->bind_param("d", $offset);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $count = 0;
                $more = false;
                while($thread = $result->fetch_assoc()){
                    $count++;
                    if($count == 11){
                        $more = true;
                        break;
                    }
                    $id = $thread['threadId'];
                    $title = $thread['threadTitle'];
                    $author = $thread['threadAuthor'];
                    $text = $thread['threadText'];
                    echo "<div class='thread'>"; // Start of block
                    echo("<a href='thread.php?id=" . $thread['threadId'] . "'>");
                    echo "<h2 >{$title}</h2></a>"; // Thread title in bold
                    if(isset($_SESSION['isAdmin'])){
                        echo "<form action='deleteThread.php' method='POST'>";
                        echo "<input type='hidden' name='threadId' value='".$id."'>";
                        echo "<input class='delete'type='submit' value='Delete'>";
                        echo "</form>";
                    }   
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
            if($more == true){
                echo("<a href='threads.php?page=".$pageNumber + 1 ."' class='np'>Next</a>");
            }
        ?>
       
    </main>
</html>