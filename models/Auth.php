<?php
class Auth
{
    private $conn;
    private $table = "members";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = ? AND is_delete = 0");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

public function findByToken($token)
{
    $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE token = ? AND is_delete = 0");
    $stmt->execute([$token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function updateToken($id, $token)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET token = ?, last_login = NOW() WHERE id = ?");
        return $stmt->execute([$token, $id]);
    }

    public function clearToken($id)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET token = NULL WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
