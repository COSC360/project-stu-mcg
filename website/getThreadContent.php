<?php
    $pageNumber = $_POST['page'];
    $threadId = $_POST['threadId'];
    include("dbConnection.php");
    $stmt = $conn->prepare("SELECT * FROM threads WHERE threadId = ?");
    $stmt->bind_param("d", $threadId);
    $jsonResult = new stdClass();
    $jsonResult->success = false;
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if($thread = $result->fetch_assoc()){
            $jsonResult->success = true;
            if($findImg = glob("./userImages/" . $thread['threadAuthor'] . ".*")){
                $thread['authorImg'] = $findImg[0]. "?t=" .time();
            }else{
                $thread['authorImg'] = 'img/blank-profile-picture-973460_1280.png?t='.time();
            }
            $jsonResult->thread = $thread;
            if($pageNumber == -1){
                $stmt = $conn->prepare("SELECT COUNT(*) AS numReplies FROM replies WHERE thread = ?");
                $stmt->bind_param("d", $threadId);
                $stmt->execute();
                $result = $stmt->get_result();
                $count = $result->fetch_assoc()['numReplies'];
                $pageNumber = floor(($count - 1)/10);
                $jsonResult->pageNumber = $pageNumber;
            }
            $stmt = $conn->prepare("SELECT * FROM replies WHERE thread = ? ORDER BY replyDate LIMIT 11 OFFSET ?");
            $offset = $pageNumber * 10;
            $stmt->bind_param("dd", $threadId, $offset);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $count = 0;
                $jsonResult->more = false;
                $jsonResult->replies = array();
                while($reply = $result->fetch_assoc()){
                    $count++;
                    if($count == 11){
                        $jsonResult->more = true;
                        break;
                    }
                    if($findImg = glob("./userImages/" . $reply['replyAuthor'] . ".*")){
                        $reply['authorImg'] = $findImg[0]. "?t=" .time();
                    }else{
                        $reply['authorImg'] = 'img/blank-profile-picture-973460_1280.png?t='.time();
                    }
                    array_push($jsonResult->replies, $reply);
                }
            }
        }
    }
    echo(json_encode($jsonResult));
    $conn->close();
?>