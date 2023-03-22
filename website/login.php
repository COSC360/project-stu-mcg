<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/form.css">
            <script src="scripts/login-validation.js"></script>
        </head>
            <?php include('header.php'); ?>
        <main>
        <?php
        $pwerror = '';
        // gotta check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // user inputs
            $login = $_POST["login"];
            $password = $_POST["password"];

            include('dbConnection.php');

            // Prepared stmnt
            $stmt = $conn->prepare("SELECT * FROM users WHERE (email = ? OR username = ?) AND password = ?");
            $stmt->bind_param("sss", $login, $login, $password);

            // Execute SQL and error check
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 1) { //just check if an entry exists for given credentials
                    //I think this saves their id to the session so you can confirm they're logged in still on other pages. 
                    $row = $result->fetch_assoc(); 
                    $username = $row['username'];
                    $_SESSION['username'] = $username;
                    
                    // change link to homepage when ready
                    header("Location: threads.php");
                    exit;
                } else {
                    $pwerror = "Incorrect username or password";
                }
            } else {
                echo "Error: " . $stmt->error;
            }

            // close db connection
            $conn->close();
            }
            ?>
            <form id="login_form" class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form_div">
                    <label>Login:</label>
                    <input class="field" type="text" name="login" placeholder="Email or Username" autofocus>
                    <label>Password:</label>
                    <input id="pass" class="field" type="password" name="password" placeholder="Password">
                    <?php echo "<p>$pwerror</p>"; ?>
                    <button class="submit" type="submit" form="login_form">Login</button>
                </div>
                <div class="info_div">
                    <p>Not yet registered? <a href="signup.php">Sign-up!</a></p>
                </div>
            </form>
        </main>
    </body>
</html>