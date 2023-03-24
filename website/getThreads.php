<?php
    $pageNumber = $_POST['page'];
    $search = $_POST['search'];
    $search = "%".$search."%";
    $region = $_POST['region'];
    include("dbConnection.php");
    $offset = $pageNumber * 10;
    if($region == 'all'){
        $stmt = $conn->prepare("SELECT * FROM threads WHERE threadTitle LIKE ? OR threadAuthor LIKE ? OR threadText like ? ORDER BY lastPost DESC LIMIT 11 OFFSET ?");
        $stmt->bind_param("sssd", $search, $search, $search, $offset);
    }else{
        $stmt = $conn->prepare("SELECT * FROM threads WHERE region = ? AND (threadTitle LIKE ? OR threadAuthor LIKE ? OR threadText like ?) ORDER BY lastPost DESC LIMIT 11 OFFSET ?");
        $stmt->bind_param("ssssd", $region, $search, $search, $search, $offset);
    }
    

    $jsonResult = new stdClass();
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $count = 0;
        $jsonResult->more = false;
        $jsonResult->threads = array();
        $jsonResult->success = True;
        while($thread = $result->fetch_assoc()){
            $count++;
            if($count == 11){
                $jsonResult->more = true;
                break;
            }
            array_push($jsonResult->threads, $thread);
        }
        echo(json_encode($jsonResult));
    }else {
        $jsonResult->success = false;
        echo(json_encode($jsonResult));
    }
    $conn->close();
    die();
?>

<?php
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