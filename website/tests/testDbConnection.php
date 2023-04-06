<?php 
class testDbConnection extends PHPUnit\Framework\TestCase{
    public function testDbConnection(){ //mock function from dbConnection file
        $servername = "localhost";
        $username_db = "cosc360user";
        $password_db = "1234";
        $dbname = "cosc360project";
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        $this->assertInstanceOf(mysqli::class, $conn);
        $this->assertTrue($conn->ping()); //ping to see if it's still connected
    }
}
?>

