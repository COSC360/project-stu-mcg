<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/threads.css">
            <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
            <script>
                lastUpdate = 0;
                $(document).ready(function(){
                    updateThreads()
                })

                function updateSearch(){
                    $('#pageNum').val(0)
                    updateThreads();
                }

                function updateThreads(){
                    console.log('update')
                    $.post("getThreads.php", {page: $('#pageNum').val(), search: $('#searchbar').val(), region: $('#regionSelect').val()}, function(result){
                        result = JSON.parse(result)
                        if(result.success){
                            if(Date.parse(result.threads[0].lastPost) > lastUpdate){
                                displayThreads(result.threads)
                            }
                            lastUpdate = Date.now();
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
                        threadLeftDiv = $("<div class='threadLeft'></div>");
                        threadRightDiv = $("<div class='threadRight'></div>");
                        threadDiv.append(`<a href='thread.php?id=${thread.threadId}'><h2 class ='threadTitle'>${thread.threadTitle}</h2></a>`);
                        threadDiv.append(`<h4 class='threadLastPost'>Last post: ${thread.lastPost}</h4>`);
                        threadLeftDiv.append(`<img class='userImg' src='${thread.authorImg}'>`);
                        threadLeftDiv.append(`<p class='author'>${thread.threadAuthor}</p>`);
                        threadRightDiv.append(`<p>${thread.threadText}</p>`);
                        authorLink = $(`<a href = 'profile.php?user=${thread.threadAuthor}'>`);
                        authorLink.append(threadLeftDiv);
                        threadDiv.append(authorLink);
                        threadDiv.append(threadRightDiv);
                        if($('#isAdmin').val() == 1){
                            threadDiv.append(`<input class='delete' type='button' value='Delete' onclick='deleteThread(${thread.threadId})'>`)
                        }
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

                function deleteThread(threadId){
                    $.post("deleteThread.php", {threadId: threadId}, function(){
                        updateThreads();
                    });
                }
            </script>
        </head>

        <?php include('header.php'); ?>
        <main>
            <div>
                <input type = "hidden" id="isAdmin" value="<?php echo(isset($_SESSION['isAdmin']))?>">
                <div class = "search">
                <h1 class = 'title'>Threads</h1>
                <?php
                    if(isset($_SESSION['username']) and !isset($_SESSION['banned'])){
                        echo ("<a class='button' href='createThread.php'>Create a thread</a>");
                    }elseif (isset($_SESSION['username']) and isset($_SESSION['banned'])){
                        echo ("<p class = 'msg'> Your account has been suspended from posting </p>");
                    }else{
                        echo ("<p class = 'msg'> You must be logged in to post</p>");
                    }
                ?>
                <label for="region">Region:</label>
                <select name="region" id="regionSelect" onchange="updateSearch()">
                   <?php
                        $selectedRegion = false;
                        if(isset($_GET['region'])){
                            $selectedRegion = $_GET['region'];
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
                        $conn->close();
                        echo('<option value="all">all</option>');
                        foreach($regions as $region){
                            if($selectedRegion && $selectedRegion == $region){
                                echo('<option value="'. $region .'" selected="selected">' . $region .'</option>');
                            }else{
                                echo('<option value="'. $region .'">' . $region .'</option>');
                            }
                        }
                    ?>
                </select>
                <input type="text" name="search" placeholder="Search" id="searchbar">
                <input class="s"type="button" value="Search" onclick="updateSearch()">
            </div>

            <div id='threadsList'></div>
            <!-- next and back buttons -->
            <input type="hidden" id="pageNum" value="0">
            <input type="button" id="previous" value="Previous" onclick="previous()">
            <input type="button" id="next" value="Next" onclick="next()">
        </main>
    </body>
</html>