<?php

class Member
{
    private $conn;
    private $table = "members";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT id, name, email, mobile FROM {$this->table} WHERE is_active = 1 AND is_delete = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT id, name, email, mobile FROM {$this->table} WHERE id = ? AND is_active = 1 AND is_delete = 0");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, email, mobile, password) VALUES (?, ?, ?,  ?)");
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['mobile'],
            $hashedPassword

        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET name = ?, email = ?, mobile = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['mobile'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_delete = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
