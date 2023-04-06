<?php
    $pageNumber = $_POST['page'];
    $search = $_POST['search'];
    $search = "%".$search."%";
    $region = $_POST['region'];
    include("dbConnection.php");
    $offset = $pageNumber * 10;
    if($region == 'All'){
        $stmt = $conn->prepare("SELECT * FROM threads WHERE threadTitle LIKE ? OR threadAuthor LIKE ? OR threadText like ? ORDER BY lastPost DESC LIMIT 11 OFFSET ?");
        $stmt->bind_param("sssd", $search, $search, $search, $offset);
    }else{
        $stmt = $conn->prepare("SELECT * FROM threads WHERE region = ? AND (threadTitle LIKE ? OR threadAuthor LIKE ? OR threadText like ?) ORDER BY lastPost DESC LIMIT 11 OFFSET ?");
        $stmt->bind_param("ssssd", $region, $search, $search, $search, $offset);
    }

    $jsonResult = new stdClass();
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $count = 0;
        $jsonResult->more = false;
        $jsonResult->threads = array();
        $jsonResult->success = True;
        while($thread = $result->fetch_assoc()){
            $count++;
            if($count == 11){
                $jsonResult->more = true;
                break;
            }
            if($findImg = glob("./userImages/" . $thread['threadAuthor'] . ".*")){
                $thread['authorImg'] = $findImg[0]. "?t=" .time();
            }else{
                $thread['authorImg'] = 'img/blank-profile-picture-973460_1280.png?t='.time();
            }
            array_push($jsonResult->threads, $thread);
        }
        echo(json_encode($jsonResult));
    }else {
        $jsonResult->success = false;
        echo(json_encode($jsonResult));
    }
    $conn->close();
?>