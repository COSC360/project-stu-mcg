<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/form.css">
            <script>
                function goBack(){
                    window.location.href = 'threads.php'
                }
            </script>
            <script src="scripts/createThread-validation.js"></script>
        </head>
            <?php include('header.php'); ?>
        <main>
        <?php

        if(!isset($_SESSION['username'])){
            header("Location: threads.php");
        }
        include('dbConnection.php');
        $regions = array();
        $stmt = $conn->prepare("SELECT region FROM regions;");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while($region = $result->fetch_assoc()){
                array_push($regions, $region['region']);
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        
        // gotta check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // user inputs
            $title = $_POST["title"];
            $text = $_POST["message"];
            $region = $_POST["region"];

            $stmt = $conn->prepare("INSERT INTO threads (threadTitle, threadDate, lastPost, threadAuthor, threadText, region) VALUES (?, NOW(), NOW(), ?, ?, ?)"); //Question marks for bind_param params
            $stmt->bind_param("ssss", $title, $_SESSION['username'], $text, $region);

            // Execute SQL statement and check for errors
            if ($stmt->execute()) {
                    //route to the thread page later
                    header("Location: threads.php");
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $conn->close();
        ?>
            <form id="createThreadForm" class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form_div">
                    <label>Thead Title:</label>
                    <input class="field" type="text" name="title" placeholder="Thread title" autofocus>
                    <label for="region">Region:</label>
                    <select name="region" >
                        <?php
                        foreach($regions as $region){
                            echo('<option value="'. $region .'">' . $region .'</option>');
                        }
                        ?>
                    </select>
                    <br>
                    <label>Message:</label>
                    <textarea class="field" name="message" rows="10" placeholder="Your message here" style="display:block"></textarea>
                    <div display="block">
                        <button class="back" type="button" name="back" style="display:inline" onclick="goBack()">Cancel</button>
                        <button class="submit" type="submit" form="createThreadForm" style="display:inline">Post</button>
                    </div>
                </div>
            </form>
        </main>
    </body>
</html>