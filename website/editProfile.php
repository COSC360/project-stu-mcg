<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/form.css">
            <script src="scripts/editProfile-validation.js"></script>
        </head>
        <?php include('header.php'); ?>
        <main>
        <?php
        if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];
        }else{
            die("not signed in");
        }

        include('dbConnection.php');

        // if form was submitted
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// user inputs
			$bio = $_POST["bio"];
			$location = $_POST["location"];

            //upload profile image
            if(isset($_FILES['profileImage'])){
                $ext = pathinfo($_FILES['profileImage']["name"], PATHINFO_EXTENSION);
                $files = glob("userImages/".$username.".*");
                foreach($files as $file){
                    unlink($file);
                }
                move_uploaded_file($_FILES['profileImage']["tmp_name"], "userImages/" . $username . "." . $ext);
                move_uploaded_file($_FILES['profileImage']["tmp_name"], "userImages/" . $username . "." . $ext);
            }

            //set new bio and location
  			$stmt = $conn->prepare($sql = "UPDATE users SET bio = ?, location = ? WHERE username = ?");
			$stmt->bind_param('sss', $bio, $location, $username);
			if($stmt->execute()){
                header("Location: profile.php");
            } else {
                echo "Error: " . $stmt->error;
            }
		}else{
            $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if($user = $result->fetch_assoc()){
                    $bio = $user['bio'];
                    $location = $user['location'];
                }else{
                    die('user not found');
                }
            } else {
                die("Error: " . $stmt->error);
            }
        }
        // close db connection
		$conn->close();
	?>
            <form id="editProfile_form" class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="form_div">
                    <h1><?php echo ($username);?></h1>
                    <label>Bio</label>
                    <textarea class="field" name="bio" rows="10" placeholder="Give some info about yourself" style="display:block"><?php echo($bio)?></textarea>
                    <label>Location</label>
                    <input class="field" type="text" name = "location" placeholder="Your location" value="<?php echo($location)?>">
                    <label>Upload new profile image:</label>
                    <input type="file" name="profileImage">
                    <button class="submit" type="submit" form="editProfile_form">update</button>
                </div>
            </form>
        </main>
    </body>
</html>