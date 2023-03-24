<?php 
if (isset($_POST['threadId'])) {
    include("dbConnection.php");

    $threadId = $_POST["threadId"];
    $stmt = $conn->prepare("DELETE FROM `threads` WHERE threadId = ? ");
    $stmt->bind_param("d",$threadId);

    if ($stmt->execute()) {
        header("Location: threads.php");
    }
    else{
        echo "Error: " . $stmt->error;
    }
    exit;
}
?>