<?php
class Database
{
    private $host = "localhost";
    private $db_name = "library_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect()
    {
        try {
            //Connect MySQL
            $tempConn = new PDO("mysql:host={$this->host}", $this->username, $this->password);
            $tempConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $tempConn->exec("CREATE DATABASE IF NOT EXISTS {$this->db_name}");
            $tempConn = null;

            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->createTables();
                    $this->updateTables();

        } catch (PDOException $e) {
            echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
            exit;
        }

        return $this->conn;
    }

    // Create tables ---------------------
    private function createTables()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255),
            author VARCHAR(255),
            genre VARCHAR(100),
            published_year INT,
            is_issued TINYINT DEFAULT 0,
            is_active TINYINT DEFAULT 1,
            is_delete TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS members (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            email VARCHAR(255),
            mobile VARCHAR(20),
            is_active TINYINT DEFAULT 1,
            is_delete TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS management (
            id INT AUTO_INCREMENT PRIMARY KEY,
            book_id INT,
            member_id INT,
            issue_date DATE,
            return_date DATE DEFAULT NULL,
            due_date DATE,
            is_active TINYINT DEFAULT 1,
            is_delete TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (book_id) REFERENCES books(id),
            FOREIGN KEY (member_id) REFERENCES members(id)
        );
    ";
        $this->conn->exec($sql);
    }
    private function updateTables()
    {
        // Check and add new columns if not exist
        $check = $this->conn->query("SHOW COLUMNS FROM members LIKE 'token'");
        if ($check->rowCount() === 0) {
            $alter = "
            ALTER TABLE members 
            ADD COLUMN password VARCHAR(255) AFTER mobile,
            ADD COLUMN token VARCHAR(255) DEFAULT NULL AFTER password,
            ADD COLUMN last_login DATETIME DEFAULT NULL AFTER token
        ";
            $this->conn->exec($alter);
        }
    }
}
