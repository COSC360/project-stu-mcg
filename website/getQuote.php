<?php
    $replyId = $_POST['replyId'];
    include("dbConnection.php");
    $stmt = $conn->prepare("SELECT * FROM replies WHERE replyId = ?");
    $stmt->bind_param("d", $replyId);
    $jsonResult = new stdClass();
    $jsonResult->success = false;
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if($reply = $result->fetch_assoc()){
            $jsonResult->success = true;
            $jsonResult->reply = $reply;
        }
    }
    echo(json_encode($jsonResult));
    //echo(json_encode($replyId));
    $conn->close();
?>