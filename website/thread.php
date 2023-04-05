<!DOCTYPE html>
<html>
    <body>
    <?php include('header.php'); ?>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/thread.css">
            <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
            <script>
                lastUpdate = 0
                intervalId = 0
                $(document).ready(function(){
                    updateThread()
                })

                function updateThread(){
                    $.post("getThreadContent.php", {page: $('#pageNum').val(), threadId: <?php echo($_GET['id'])?>}, function(res){
                        res = JSON.parse(res)
                        if(res.success){
                            console.log(Date.parse(res.thread.lastPost))
                            console.log(lastUpdate);
                            if(Date.parse(res.thread.lastPost) > lastUpdate){
                                displayThread(res)
                            }

                            if($('#pageNum').val() == -1){
                                $('#pageNum').val(res.pageNumber);
                            }
                        }else{
                            console.log('error');
                        }
                        if($('#pageNum').val() == 0){
                            $('#previous').hide()
                            $('#first').hide()
                        } else {
                            $('#previous').show()
                            $('#first').show()
                        }
                        if(res.more){
                            $('#next').show()
                            $('#last').show()
                        } else {
                            $('#next').hide()
                            $('#last').hide()
                            if(intervalId == 0){
                                intervalId = setInterval(updateThread, 10000)
                            }
                            lastUpdate = Date.now();
                        }
                    });
                }

                function displayThread(threadContent){
                    $('#breadcrum').empty();
                    $('#breadcrum').append(`<h3><a href="threads.php">Threads</a>/<a href="threads.php?region=${threadContent.thread.region}">${threadContent.thread.region}</a>/${threadContent.thread.threadTitle}</h3>`);
                    threadLeftDiv = $("<div class='threadLeft'></div>");
                    threadRightDiv = $("<div class='threadRight'></div>");
                    $('#threadPost').empty();
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
                        replyRightDiv.append(generateReplyContent(reply.replyText));
                        replyRightDiv.append(`Quote:<input type="checkbox" name="quotes[]" value="[quote=${reply.replyId}]${reply.replyAuthor}: ${removeQuotes(reply.replyText)}[/quote=${reply.replyId}]">`)
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

                function generateReplyContent(replyText){
                    reply = $('<div></div>');
                    while(replyText != ''){
                        quoteStart = replyText.search("\\[quote=([0-9]+)\\]")
                        if(quoteStart == -1){
                            reply.append(`<p>${replyText}</p>`);
                            return reply;
                        }
                        reply.append(`<p>${replyText.substring(0, quoteStart)}</p>`);
                        replyText = replyText.substring(quoteStart);
                        quoteId = replyText.match("[0-9]+")
                        let quote = $(`<div class = 'quote'></div>`);
                        reply.append(quote);
                        $.post("getQuote.php", {replyId: quoteId[0]}, function(res, arguments){
                            reply = JSON.parse(res).reply;
                            quote.append(`<p class = quoteAuthor>${reply.replyAuthor}:<p>`)
                            quote.append(generateReplyContent(reply.replyText));
                        });
                        quoteEnd = replyText.indexOf(`[/quote=${quoteId}]`);
                        replyText = replyText.substring(quoteEnd + `[/quote=${quoteId}]`.length);
                    }   
                    return reply;
                }

                function removeQuotes(replyText){
                    reply = '';
                    while(replyText != ''){
                        quoteStart = replyText.search("\\[quote=([0-9]+)\\]")
                        if(quoteStart == -1){
                            reply += replyText;
                            return reply;
                        }
                        reply += replyText.substring(0, quoteStart);
                        replyText = replyText.substring(quoteStart);
                        quoteId = replyText.match("[0-9]+")
                        quoteEnd = replyText.indexOf(`[/quote=${quoteId}]`);
                        replyText = replyText.substring(quoteEnd + `[/quote=${quoteId}]`.length);
                    }   
                    return reply
                }

                function next(){
                    $('#pageNum').val($('#pageNum').val() + 1);
                    updateThread();
                }
                function last(){
                    $('#pageNum').val(-1);
                    updateThread();
                }
                function previous(){
                    $('#pageNum').val($('#pageNum').val() - 1);
                    clearInterval(intervalId)
                    intervalId = 0
                    lastUpdate = 0
                    updateThread();
                }
                function first(){
                    $('#pageNum').val(0);
                    clearInterval(intervalId)
                    intervalId = 0
                    lastUpdate = 0
                    updateThread();
                }
            </script>
        </head>
        <main>
            <div id='breadcrum'></div>
            <div id='threadPost' class='thread'></div>
            <form method='post' action ='createReplyForm.php'>
                <input type="hidden" id="id" name="id" value="<?php echo($_GET['id'])?>">
                <?php
                    if(!isset($_GET['id'])){
                        die("null thread");
                    }
                    $threadId = $_GET['id'];
                    if(isset($_SESSION['username'])){
                        if(isset($_SESSION['banned'])){
                            echo("<div class='replyButton'>");
                            echo("<h3>Your account has been suspended from posting</h3></div>");
                        }else{
                        echo("<button type='submit' class='replyButton'>");
                        echo("<h3>Reply to thread</h3></button>");
                        }
                    }else{
                        echo("<div class='replyButton'>");
                        echo("<h3>Must be logged in to reply</h3></div>");
                    }
                ?>
                <div id='threadReplies'>
                </div>
                <?php
                    $threadId = $_GET['id'];
                    if(isset($_SESSION['username'])){
                        if(isset($_SESSION['banned'])){
                            echo("<div class='replyButton'>");
                            echo("<h3>Your account has been suspended from posting</h3></div>");
                        }else{
                            echo("<button type='submit' class='replyButton'>");
                            echo("<h3>Reply to thread</h3></button>");
                        }
                    }else{
                        echo("<div class='replyButton' id = 'bottomReplyButton'>");
                        echo("<h3>Must be logged in to reply</h3></div>");
                    }
                ?>
            </form>
            <!-- next and back buttons -->
            <input type="hidden" id="pageNum" value="<?php if(isset($_GET['page'])){echo($_GET['page']);} else{echo(0);} ?>">
            <input type="button" id="first" value="First" onclick="first()">
            <input type="button" id="previous" value="Previous" onclick="previous()">
            <input type="button" id="next" value="Next" onclick="next()">
            <input type="button" id="last" value="Last" onclick="last()">
        </main>
    </body>
</html>