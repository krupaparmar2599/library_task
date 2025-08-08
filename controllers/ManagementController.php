<?php

require_once './models/Management.php';

class ManagementController
{
    private $management;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->management = new Management($conn);
    }

    public function issueBook($data)
    {
        if (
            !isset($data['book_id']) ||
            !isset($data['member_id']) ||
            !isset($data['issue_date']) ||
            !isset($data['due_date'])
        ) {
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields"]);
            return;
        }

        // Check book and members are valid 
        $checkBookQuery = "SELECT id FROM books WHERE id = :book_id AND is_active = 1 AND is_delete = 0";
        $checkBookStmt = $this->conn->prepare($checkBookQuery);
        $checkBookStmt->bindParam(':book_id', $data['book_id']);
        $checkBookStmt->execute();
        if ($checkBookStmt->rowCount() === 0) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid or inactive/deleted book"]);
            return;
        }

        $checkMemberQuery = "SELECT id FROM members WHERE id = :member_id AND is_active = 1 AND is_delete = 0";
        $checkMemberStmt = $this->conn->prepare($checkMemberQuery);
        $checkMemberStmt->bindParam(':member_id', $data['member_id']);
        $checkMemberStmt->execute();
        if ($checkMemberStmt->rowCount() === 0) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid or inactive/deleted member"]);
            return;
        }

        $response = $this->management->issueBook(
            $data['book_id'],
            $data['member_id'],
            $data['issue_date'],
            $data['due_date']
        );

        if (is_array($response) && isset($response['status']) && $response['status']) {
            echo json_encode(["message" => "Book issued successfully"]);
        } elseif (is_array($response) && isset($response['error'])) {
            http_response_code(400);
            echo json_encode(["error" => $response['error']]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Unexpected error occurred"]);
        }
    }

    public function returnBook($data)
    {
        if (
            !isset($data['book_id']) ||
            !isset($data['member_id']) ||
            !isset($data['return_date'])
        ) {
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields"]);
            return;
        }

        $success = $this->management->returnBook(
            $data['book_id'],
            $data['member_id'],
            $data['return_date']
        );

        if ($success) {
            echo json_encode(["message" => "Book returned successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to return book"]);
        }
    }

    public function getOverdueBooks()
    {
        $books = $this->management->getOverdueBooks();
        echo json_encode($books);
    }

    public function getCurrentlyIssuedBooks()
    {
        $books = $this->management->getCurrentlyIssuedBooks();
        echo json_encode($books);
    }
}
