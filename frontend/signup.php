<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/form.css">
            <script src="scripts/signup-validation.js"></script>
        </head>
        <header>
            <?php include('header.html'); ?>
        </header>
        <main>
        <?php
        // gotta check if form was submitted
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// user inputs
			$username = $_POST["username"];
			$email = $_POST["email"];
			$password = $_POST["password"];

            // Connect to xampp local, change for school server
			$servername = "localhost";
			$username_db = "mac";
			$password_db = "1234";
			$dbname = "outside";
			$conn = new mysqli($servername, $username_db, $password_db, $dbname);

			// Check connection
			if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			}

			// Prepared stmnt
			$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)"); //Question marks for bind_param params
			$stmt->bind_param("sss", $username, $email, $password);
			//should probs check if username already exists

			// Execute SQL statement and check for errors
			if ($stmt->execute()) {
					//route to login page
                    header("Location: login.php");
			} else {
			    echo "Error: " . $stmt->error;
			}

			// close db connection
			$conn->close();
		}
	?>
            <form id="login_form" class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form_div">
                    <label>Username</label>
                    <input class="field" type="text" name = "username" placeholder="Username" autofocus>
                    <label>Email:</label>
                    <input id="pass" class="field" type="email" name = "email"placeholder="Email">
                    <label>Password:</label>
                    <input id="pass" class="field" type="password" name = "password" placeholder="Password">                    
                    <label>Confirm Password:</label>
                    <input id="pass" class="field" type="password" placeholder="Confirm Password">
                    <button class="submit" type="submit" form="login_form">Sign-up</button>
                </div>
            </form>
        </main>
    </body>
</html>