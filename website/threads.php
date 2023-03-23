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
            if(isset($_SESSION['username'])){
                echo ("<a href='createThread.php'>Create a thread</a>");
            } else{
                echo ("you must be loggeed in to create a thread");
            }
        ?>
        <?php
$threadId = 'threadId';
$threadTitle = 'threadTitle';
$threadAuthor = 'threadAuthor';
$threadText = 'threadText';

include('dbConnection.php');
$stmt = $conn->prepare("SELECT threadTitle, threadAuthor, threadText FROM threads");

// Check if the query was successful
if ($stmt->execute()) {
    $result = $stmt->get_result();
    // Iterate through each row in the result set
    while ($row = $result->fetch_assoc()) {
        $title = $row['threadTitle'];
        $author = $row['threadAuthor'];
        $text = $row['threadText'];
        // Display the thread information in a block
        echo "<div class='thread'>"; // Start of block
        echo "<h2 >{$title}</h2>"; // Thread title in bold
        echo "<p class='author' >Author: {$author}</p>"; // Author underneath
        echo "<p>{$text}</p>"; // Preview of thread text
        echo "</div>"; // End of block
    }
} else {
    // Display an error message if the query failed
    echo "Error: " . mysqli_error($conn);
}


            // Close the database connection
			$conn->close();
        ?>
    </main>
</html>