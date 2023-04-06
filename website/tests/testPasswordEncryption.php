<?php
function validate_user_credentials($login, $password) { // mock validation function from login file
    $conn = include('./../dbConnection.php');

    $stmt = $conn->prepare("SELECT * FROM users WHERE (email = ? OR username = ?) AND password = ?");
    $encrypedPW = md5($password);
    $stmt->bind_param("sss", $login, $login, $encrypedPW);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $username = $row['username'];
            $_SESSION['username'] = $username;
            $isAdmin = $row['isAdmin'];
            $enabled = $row['enabled'];
            if($enabled == 1){
                $_SESSION['banned'] = True;
                header("Location: banned.php");
                exit();
            }
            if($isAdmin == 1){
                $_SESSION['isAdmin'] = True;
            }
            header("Location: threads.php");
            exit;
        } else {
            return "Incorrect username or password";
        }
    } else {
        return "Error: " . $stmt->error;
    }

    $conn->close();
}

class PasswordEncryptionTest extends \PHPUnit\Framework\TestCase
{
    public function testPasswordEncryption()
    {
        $login = "testuser";
        $password = "testpassword";

        $result = validate_user_credentials($login, $password);

        $this->assertEquals($result, "Incorrect username or password"); //Can't login with incorrect credentials
    }
}

?>
