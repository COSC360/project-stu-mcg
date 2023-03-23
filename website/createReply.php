<!DOCTYPE html>
<html>

<body>

    <head>
        <link rel="stylesheet" href="css/all.css">
        <link rel="stylesheet" href="css/form.css">

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!isset($_GET['id'])) {
                die("null thread");
            }
            $threadId = $_GET['id'];
        }
        ?>

        <script>
            function goBack(threadId) {
                window.location.href = 'thread.php?id=<?php echo($_GET['id']); ?>';
            }
        </script>
        <script src="scripts/createReply-validation.js"></script>
    </head>
    <?php include('header.php'); ?>
    <main>
        <?php
        if (!isset($_SESSION['username'])) {
            header("Location: threads.php");
        }

        include('dbConnection.php');

        // gotta check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // user inputs
            $threadId = $_POST["threadId"];
            $text = $_POST["message"];
            $username = $_POST["username"];

            $stmt = $conn->prepare("INSERT INTO replies (thread, replyDate, replyAuthor, replyText) VALUES (?, NOW(), ?, ?)"); //Question marks for bind_param params
            $stmt->bind_param("sss", $threadId, $_SESSION['username'], $text);

            // Execute SQL statement and check for errors
            if ($stmt->execute()) {
                //route to the thread page later
                header("Location: thread.php?id=" . $threadId);
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            $stmt = $conn->prepare("SELECT * FROM threads WHERE threadId = ?");
            $stmt->bind_param("d", $threadId);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if(!$thread = $result->fetch_assoc()){
                    die("thread does not exist");
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $conn->close();
        ?>

        <form id="createReplyForm" class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
            method="post">
            <div class="form_div">
                <h2>Replying to: <?php echo($thread['threadTitle']); ?></h2>
                <h3><?php echo($thread['threadAuthor']); ?>:</h3>
                <p><?php echo($thread['threadText']); ?></p>
                <label>Reply:</label>
                <textarea class="field" name="message" rows="10" placeholder="Your reply here"style="display:block"></textarea>
                <input type="hidden" name="threadId" value="<?php echo($threadId); ?>">
                <input type="hidden" name="username" value="<?php echo($_SESSION['username']); ?>">
                <div display="block">
                    <button class="back" type="button" name="back" style="display:inline" onclick="goBack()">Cancel</button>
                    <button class="submit" type="submit" form="createReplyForm" style="display:inline">Post</button>
                </div>
            </div>
        </form>
    </main>
</body>

</html>