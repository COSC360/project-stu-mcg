<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/admin.css">
            <script src="scripts/login-validation.js"></script>
        </head>
        <?php include('header.php'); ?>
        <?php
            if(!isset($_SESSION['isAdmin'])){
                die("Must be signed in as admin user to view this page");
            }
        ?>
    <main>
    <div class="manage">
        <form method="post" action="">
            <input type="text" name="search" placeholder="Search">
            <input type="submit" value="Search">
        </form>
        <?php
            include("dbConnection.php");

            // search input
            $search = "";
            if (isset($_POST['search'])) {
                $search = $_POST['search'];
            }

            $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE '%$search%' OR email LIKE '%$search%'"); 
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                echo '<table>';
                echo '<tr><th>Username</th><th>Email</th><th>Password</th><th>Priviledges</th></tr>';

                while ($row = $result->fetch_assoc()) {
                    $username = $row['username'];
                    $email = $row['email'];
                    $password = $row['password'];
                    $isAdmin = $row['isAdmin'];
                    $enabled = $row['enabled'];
                    echo '<tr>';
                    echo '<td><a href = "profile.php?user=' . $username .'">' . $username . '</td>';
                    echo '<td>' . $email . '</td>';
                    echo '<td>' . $password . '</td>';
                    echo "<td><form action='changeState.php' method='POST'>";
                    echo "<input type='hidden' name='username' value='".$username."'>";
                    echo "<input type='hidden' name='enabled' value='".$enabled."'>"; 
                    if($isAdmin == 0){
                        echo ("<input type='submit' value='".($enabled == 0 ? 'Disable': 'Enable')."'>");
                    }else{
                        echo("Admin");
                    }
                    echo "</form></td>";
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo "Error: " . $stmt->error;
            }
            $conn->close();
        ?>

            </div>
        </main>
    </body>
</html>