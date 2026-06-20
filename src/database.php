<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "evenuehubdb";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        } catch (Exception $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
