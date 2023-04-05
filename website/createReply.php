<?php
    include('dbConnection.php');
    // gotta check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // user inputs
        $threadId = $_POST["threadId"];
        $text = $_POST["message"];
        $username = $_POST["username"];

        $stmt = $conn->prepare("INSERT INTO replies (thread, replyDate, replyAuthor, replyText) VALUES (?, NOW(), ?, ?)"); //Question marks for bind_param params
        $stmt->bind_param("sss", $threadId, $username, $text);

        // Execute SQL statement and check for errors
        if ($stmt->execute()) {
            $stmt = $conn->prepare("UPDATE threads SET lastPost = NOW() WHERE threadId = ?");
            $stmt->bind_param('d', $threadId);
            $stmt->execute();
            $conn->close();
            //route to the thread page later
            header("Location: thread.php?id=" . $threadId);
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $conn->close();
?>