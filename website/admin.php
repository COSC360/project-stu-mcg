<!DOCTYPE html>
<html>
    <body>
        <head>
            <link rel="stylesheet" href="css/all.css">
            <link rel="stylesheet" href="css/admin.css">
            <script src="scripts/login-validation.js"></script>
        </head>
        <?php include('header.php'); ?>
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
            $sql = "";

            $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE '%$search%' OR firstName LIKE '%$search%' OR lastName LIKE '%$search%' OR email LIKE '%$search%'"); 
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                echo '<table>';
                echo '<tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Password</th><th>Profile Image</th><th>Is Admin</th><th>Priviledges</th></tr>';

                while ($row = $result->fetch_assoc()) {
                    $username = $row['username'];
                    $firstName = $row['firstName'];
                    $lastName = $row['lastName'];
                    $email = $row['email'];
                    $password = $row['password'];
                    $profileImage = $row['profileImage'];
                    $isAdmin = $row['isAdmin'];
                    $enabled = $row['enabled'];
                    echo '<tr>';
                    echo '<td>' . $username . '</td>';
                    echo '<td>' . $firstName . '</td>';
                    echo '<td>' . $lastName . '</td>';
                    echo '<td>' . $email . '</td>';
                    echo '<td>' . $password . '</td>';
                    echo '<td>' . $profileImage . '</td>';
                    echo '<td>' . ($isAdmin == 1 ? 'Yes': 'No') . '</td>';
                    echo "<td><form action='changeState.php' method='POST'>";
                    echo "<input type='hidden' name='username' value='".$username."'>";
                    echo "<input type='hidden' name='enabled' value='".$enabled."'>"; 
                    echo "<input type='submit' value='".($enabled == 0 ? 'Disable': 'Enable')."'>";
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