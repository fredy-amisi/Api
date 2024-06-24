<?php

/**
 * Database Connection
 */
class DbConnect
{
    private $server = 'localhost';
    private $dbname = 'E-Attendance';
    private $user = 'root';
    private $pass = '';
    public $conn; 

    public function __construct()
    {
        try {
            $this->conn = new PDO('mysql:host=' . $this->server . ';dbname=' . $this->dbname, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            echo "Database Error: " . $e->getMessage();
        }
    }

    // You can add additional methods here if needed
}

?>
