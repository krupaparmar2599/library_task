<?php

class Book
{
    private $conn;
    private $table = 'books';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT id, title, author, genre, published_year FROM books WHERE is_active = 1 AND is_delete = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ? AND is_active = 1 AND is_delete = 0");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (title, author, genre, published_year) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'],
            $data['author'],
            $data['genre'],
            $data['published_year']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET title = ?, author = ?, genre = ?, published_year = ? WHERE id = ?");
        return $stmt->execute([
            $data['title'],
            $data['author'],
            $data['genre'],
            $data['published_year'],
            $id
        ]);
    }

    public function softDelete($id)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_delete = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
