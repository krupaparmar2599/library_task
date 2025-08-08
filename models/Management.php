<?php

class Management
{
    private $conn;
    private $table = "management";

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function issueBook($bookId, $memberId, $issueDate, $dueDate)
    {
        try {
            $query = "INSERT INTO management (book_id, member_id, issue_date, due_date, is_active, is_delete, created_at, updated_at)
                  VALUES (:book_id, :member_id, :issue_date, :due_date, 1, 0, NOW(), NOW())";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':book_id', $bookId);
            $stmt->bindParam(':member_id', $memberId);
            $stmt->bindParam(':issue_date', $issueDate);
            $stmt->bindParam(':due_date', $dueDate);

            if ($stmt->execute()) {
                return ['status' => true];
            } else {
                return ['status' => false, 'error' => 'Failed to insert record'];
            }
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function returnBook($book_id, $member_id, $return_date)
    {
        $query = "UPDATE {$this->table} 
              SET return_date = :return_date 
              WHERE book_id = :book_id 
                AND member_id = :member_id 
                AND return_date IS NULL 
                AND is_delete = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":book_id", $book_id);
        $stmt->bindParam(":member_id", $member_id);
        $stmt->bindParam(":return_date", $return_date);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function getOverdueBooks()
    {
        $query = "SELECT b.title AS book_title, mem.name AS member_name, m.issue_date, m.due_date
                  FROM {$this->table} m
                  JOIN books b ON m.book_id = b.id
                  JOIN members mem ON m.member_id = mem.id
                  WHERE m.return_date IS NULL AND m.due_date < CURDATE() AND m.is_delete = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurrentlyIssuedBooks()
    {
        $query = "SELECT m.*, b.title AS book_title, mem.name AS member_name
              FROM {$this->table} m
              JOIN books b ON m.book_id = b.id
              JOIN members mem ON m.member_id = mem.id
              WHERE m.return_date IS NULL 
                AND m.due_date >= CURDATE()
                AND m.is_delete = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
