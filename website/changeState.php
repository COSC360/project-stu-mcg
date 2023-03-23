<?php
if (isset($_POST['username']) && isset($_POST['enabled'])) {
    include("dbConnection.php");
    $username = $_POST["username"];
    $currentState = $_POST["enabled"];
    if($currentState == 0){
        $newState = 1;
    }elseif($currentState == 1){
        $newState = 0;
    }
    $stmt = $conn->prepare("UPDATE users SET enabled = ? WHERE username = ?");
    $stmt->bind_param("ds", $newState,$username);

    if ($stmt->execute()) {
        header("Location: admin.php");
    }
    else{
        echo "Error: " . $stmt->error;
    }

    exit;
}
?>