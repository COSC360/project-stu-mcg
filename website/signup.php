<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/form.css">
            <script src="scripts/signup-validation.js"></script>
        </head>
        <?php include('header.php'); ?>
        <main>
        <?php
        $errorMessage = "";
        // gotta check if form was submitted
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// user inputs
			$username = $_POST["username"];
			$email = $_POST["email"];
			$password = $_POST["password"];

            include('dbConnection.php');

			// check if username already exists
			
  			$stmt = $conn->prepare($sql = "SELECT * FROM users WHERE username = ? OR email = ?;");
			$stmt->bind_param('ss', $username, $email);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($result->fetch_assoc()) {
				$errorMessage = "Username/email already taken";
			}
            else{
                // Prepared stmnt
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)"); //Question marks for bind_param params
                $encrypedPW = md5($password);
                $stmt->bind_param("sss", $username, $email, $encrypedPW);

                // Execute SQL statement and check for errors
                if ($stmt->execute()) {
                        //route to login page
                        header("Location: login.php");
                } else {
                    echo "Error: " . $stmt->error;
                }
            }
			// close db connection
			$conn->close();
		}
	?>
            <form id="login_form" class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="form_div">
                    <label>Username</label>
                    <input class="field" type="text" name = "username" placeholder="Username" autofocus>
                    <label>Email:</label>
                    <input id="pass" class="field" type="email" name = "email"placeholder="Email">
                    <label>Password:</label>
                    <input id="pass" class="field" type="password" name = "password" placeholder="Password">                    
                    <label>Confirm Password:</label>
                    <input id="pass" class="field" type="password" placeholder="Confirm Password">
                    <label>Profile Image (optional):</label>
                    <input type="file" name="profileImage">
                    <?php echo "<p>$errorMessage</p>"; ?> 
                    <button class="submit" type="submit" form="login_form">Sign-up</button>
                </div>
            </form>
        </main>
    </body>
</html>