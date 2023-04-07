<!DOCTYPE html>
<html>

<body>

    <head>
        <link rel="stylesheet" href="css/all.css">
        <link rel="stylesheet" href="css/form.css">

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_POST['id'])) {
                die("null thread");
            }
            $threadId = $_POST['id'];
        }
        ?>

        <script>
            function goBack(threadId) {
                window.location.href = 'thread.php?id=<?php echo($_POST['id']); ?>';
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
        $stmt = $conn->prepare("SELECT * FROM threads WHERE threadId = ?");
        $stmt->bind_param("d", $threadId);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if(!($thread = $result->fetch_assoc())){
                die("thread does not exist");
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        ?>

        <form id="createReplyForm" class="form" action="createReply.php"
            method="post">
            <div class="form_div">
                <h2>Replying to: <?php echo($thread['threadTitle']); ?></h2>
                <h3><?php echo($thread['threadAuthor']); ?>:</h3>
                <p><?php echo($thread['threadText']); ?></p>
                <label>Reply:</label>
                <textarea class="field" name="message" rows="10" placeholder="Your reply here"style="display:block"><?php
                    if(isset($_POST['quotes'])){
                        $quotes = $_POST['quotes'];
                        if(is_array($quotes)){
                            foreach($quotes as $quote) {
                                echo('&#13;&#10;');
                                echo($quote);
                                echo('&#13;&#10;');
                            }
                        }else{
                            echo('&#13;&#10;');
                            echo($quotes);
                            echo('&#13;&#10;');
                        }
                    }
                ?></textarea>
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