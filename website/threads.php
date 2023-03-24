<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/threads.css">
            <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
            <script>
                $(document).ready(function(){
                    updateThreads()
                })

                function updateSearch(){
                    $('#pageNum').val(0)
                    updateThreads();
                }

                function updateThreads(){
                    console.log($('#searchbar'))
                    $.post("getThreads.php", {page: $('#pageNum').val(), search: $('#searchbar').val(), region: $('#regionSelect').val()}, function(result){
                        result = JSON.parse(result)
                        if(result.success){
                            displayThreads(result.threads)
                        }else{
                            console.log('error');
                        }
                        if($('#pageNum').val() == 0)
                        {
                            $('#previous').hide()
                        } else {
                            $('#previous').show()
                        }
                        if(result.more){
                            $('#next').show()
                        } else {
                            $('#next').hide()
                        }
                    });
                }

                function displayThreads(threads){
                    threadsList = $('#threadsList');
                    threadsList.empty();
                    threads.forEach(function(thread){
                        console.log(thread)
                        threadDiv = $("<div class='thread'></div>");
                        threadDiv.append(`<a href='thread.php?id=${thread.threadId}'><h2 >${thread.threadTitle}</h2></a>`);
                        threadDiv.append(`<p class='author' >Author: ${thread.threadAuthor}</p>`);
                        threadDiv.append(`<p>${thread.threadText}</p>`); 
                        threadsList.append(threadDiv);
                    });
                }

                function next(){
                    $('#pageNum').val($('#pageNum').val() + 1);
                    updateThreads();
                }
                function previous(){
                    $('#pageNum').val($('#pageNum').val() - 1);
                    updateThreads();
                }
            </script>
        </head>
        <?php include('header.php'); ?>
        <main>
            <div class = "search">
            <h1 class = 'title'>Threads</h1>
            <?php
                if(isset($_SESSION['username']) and !isset($_SESSION['banned'])){
                    echo ("<a class='button' href='createThread.php'>Create a thread</a></div>");
                }elseif (isset($_SESSION['username']) and isset($_SESSION['banned'])){
                    echo ("<p class = 'msg'> Your account has been suspended from posting </p></div>");
                }else{
                    echo ("<p class = 'msg'> You must be logged in to post</p></div>");
                }
            ?>
            <label for="region">Region:</label>
            <select name="region" id="regionSelect" onchange="updateSearch()">
                <?php
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
                    $conn->close();
                    echo('<option value="all">all</option>');
                    foreach($regions as $region){
                        echo('<option value="'. $region .'">' . $region .'</option>');
                    }
                ?>
            </select>
            <input type="text" name="search" placeholder="Search" id="searchbar">
            <input type="button" value="Search" onclick="updateSearch()">
            <div id='threadsList'></div>
            <!-- next and back buttons -->
            <input type="hidden" id="pageNum" value="0">
            <input type="button" id="previous" value="Previous" onclick="previous()">
            <input type="button" id="next" value="Next" onclick="next()">
        </main>
    </body>
</html>