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
            <form method="post" action=""><input type="text" name="search" placeholder="Search"><input type="submit" value="Search"></form>
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
                echo '<tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Password</th><th>Profile Image</th><th>Is Admin</th><th>Enabled</th></tr>';

                while ($row = $result->fetch_assoc()) {
                    $username = $row['username'];
                    $firstName = $row['firstName'];
                    $lastName = $row['lastName'];
                    $email = $row['email'];
                    $password = $row['password'];
                    $profileImage = $row['profileImage'];
                    $isAdmin = $row['isAdmin'];
                    echo '<tr>';
                    echo '<td>' . $username . '</td>';
                    echo '<td>' . $firstName . '</td>';
                    echo '<td>' . $lastName . '</td>';
                    echo '<td>' . $email . '</td>';
                    echo '<td>' . $password . '</td>';
                    echo '<td>' . $profileImage . '</td>';
                    echo '<td>' . $isAdmin . '</td>';
                   // echo '<td><label><input type="checkbox" name="enabled[]" value="' . $row['username'] . '"' . ($row['enabled'] ? ' checked' : '') . '> Enabled</label></td>';
                    echo '</tr>';
                }
                echo '</table>';
                }else {
                echo "Error: " . $stmt->error;
                }
                // enabled stuff
                // if (isset($_POST['enabled'])) {
                //     foreach ($_POST['enabled'] as $username) {
                //         $enabled = isset($_POST['enabled_' . $username]);
                //         $sql = "UPDATE users SET enabled = " . ($enabled ? 1 : 0) . " WHERE username = '$username'";
                //         mysqli_query($conn, $sql);
                //     }
                // }

                // Close the database connection
                $conn->close();

                ?>

            </div>
        </main>
    </body>
</html>