<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/thread.css">
            <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
            <script>
                $(document).ready(function(){
                    updateThread()
                })

                function updateThread(){
                    $.post("getThreadContent.php", {page: $('#pageNum').val(), threadId: <?php echo($_GET['id'])?>}, function(res){
                        res = JSON.parse(res)
                        console.log(res)
                        if(res.success){
                            displayThread(res);
                        }else{
                            console.log('error');
                        }
                        if($('#pageNum').val() == 0){
                            $('#previous').hide()
                        } else {
                            $('#previous').show()
                        }
                        if(res.more){
                            $('#next').show()
                        } else {
                            $('#next').hide()
                        }
                    });
                }

                function displayThread(threadContent){
                    $('#breadcrum').append(`<h3><a href="threads.php">Threads</a>/<a href="threads.php?region=${threadContent.thread.region}">${threadContent.thread.region}</a>/${threadContent.thread.threadTitle}</h3>`);
                    threadLeftDiv = $("<div class='threadLeft'></div>");
                    threadRightDiv = $("<div class='threadRight'></div>");
                    $('#threadPost').append(`<h2 class ='threadTitle'>${threadContent.thread.threadTitle}</h2>`);
                    threadLeftDiv.append(`<img class='userImg' src='${threadContent.thread.authorImg}'>`);
                    threadLeftDiv.append(`<p class='author'>${threadContent.thread.threadAuthor}</p>`);
                    threadRightDiv.append(`<p>${threadContent.thread.threadText}</p>`);
                    authorLink = $(`<a href = 'profile.php?user=${threadContent.thread.threadAuthor}'>`);
                    authorLink.append(threadLeftDiv);
                    $('#threadPost').append(authorLink);
                    $('#threadPost').append(threadRightDiv);
                    repliesList = $('#threadReplies');
                    repliesList.empty();
                    threadContent.replies.forEach(function(reply){
                        replyDiv = $("<div class='reply'></div>");
                        replyLeftDiv = $("<div class='replyLeft'></div>");
                        replyRightDiv = $("<div class='replyRight'></div>");
                        replyLeftDiv.append(`<img class='userImg' src='${reply.authorImg}'>`);
                        replyLeftDiv.append(`<p class='author'>${reply.replyAuthor}</p>`);
                        replyRightDiv.append(`<p>${reply.replyText}</p>`);
                        authorLink = $(`<a href = 'profile.php?user=${reply.replyAuthor}'>`);
                        authorLink.append(replyLeftDiv);
                        replyDiv.append(authorLink);
                        replyDiv.append(replyRightDiv);
                        repliesList.append(replyDiv);
                    });
                    if(threadContent.replies.length > 3){
                        $('#bottomReplyButton').show();
                    }else{
                        $('#bottomReplyButton').hide();
                    }
                }

                function next(){
                    $('#pageNum').val($('#pageNum').val() + 1);
                    updateThread();
                }
                function previous(){
                    $('#pageNum').val($('#pageNum').val() - 1);
                    updateThread();
                }
            </script>
        </head>
        <?php include('header.php'); ?>
        <main>
        <div id='breadcrum'></div>
        <div id='threadPost' class='thread'></div>
            <div class='replyButton'>
                <?php
                    if(!isset($_GET['id'])){
                        die("null thread");
                    }
                    $threadId = $_GET['id'];
                    if(isset($_SESSION['username'])){
                        echo("<a href='createReply.php?id=" . $threadId . "'><h3>Reply to thread</h3></a>");
                    }else{
                        echo("<h3>Must be logged in to reply</h3>");
                    }
                ?>
            </div>
            <div id='threadReplies'></div>
            <div class='replyButton' id = 'bottomReplyButton'>
                <?php
                    if(!isset($_GET['id'])){
                        die("null thread");
                    }
                    $threadId = $_GET['id'];
                    if(isset($_SESSION['username'])){
                        echo("<a href='createReply.php?id=" . $threadId . "'><h3>Reply to thread</h3></a>");
                    }else{
                        echo("<h3>Must be logged in to reply</h3>");
                    }
                ?>
            </div>
            <!-- next and back buttons -->
            <input type="hidden" id="pageNum" value="0">
            <input type="button" id="previous" value="Previous" onclick="previous()">
            <input type="button" id="next" value="Next" onclick="next()">
        </main>
    </body>
</html>