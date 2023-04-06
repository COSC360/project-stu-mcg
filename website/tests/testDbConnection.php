<?php 
class testDbConnection extends PHPUnit\Framework\TestCase{
    public function testDbConnection(){ 
        include('../dbConnection.php');

        $this->assertInstanceOf(mysqli::class, $conn);
        $this->assertTrue($conn->ping()); //ping to see if it's still connected
    }
}
?>

