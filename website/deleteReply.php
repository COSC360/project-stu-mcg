<?php 
if (isset($_POST['replyId'])) {
    include("dbConnection.php");
    $replyId = $_POST["replyId"];
    $stmt = $conn->prepare("DELETE FROM replies WHERE replyId = ?");
    $stmt->bind_param("d", $replyId);
    $stmt->execute();
    $conn->close();
}
?>