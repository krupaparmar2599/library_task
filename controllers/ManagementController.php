<?php

require_once './models/Management.php';

include_once './models/Auth.php';

class ManagementController
{
    private $management;

    private $authModel;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->management = new Management($conn);
        $this->authModel = new Auth($conn);
    }

    public function issueBook($data)
    {
        // Check token ------------ start
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Authorization token is required"]);
            return;
        }

        $authHeader = trim($headers['Authorization']);
        if (stripos($authHeader, 'Bearer ') === 0) {
            $token = substr($authHeader, 7);
        } else {
            $token = $authHeader;
        }

        $user = $this->authModel->findByToken($token);
        if (!$user) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid or expired token"]);
            return;
        }
        // Check token ------------ end

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

        // Check book and members are valid -- book alredy issued --------------
        if (!$this->management->isBookActive($data['book_id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid or inactive/deleted book"]);
            return;
        }

        if (!$this->management->isMemberActive($data['member_id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid or inactive/deleted member"]);
            return;
        }

        if ($this->management->isBookAlreadyIssued($data['book_id'], $data['member_id'])) {
            http_response_code(400);
            echo json_encode(["error" => "This book is already issued to this member and not returned yet"]);
            return;
        }
        // -------------------------
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
        // Check token ------------ start
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Authorization token is required"]);
            return;
        }

        $authHeader = trim($headers['Authorization']);
        if (stripos($authHeader, 'Bearer ') === 0) {
            $token = substr($authHeader, 7);
        } else {
            $token = $authHeader;
        }

        $user = $this->authModel->findByToken($token);
        if (!$user) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid or expired token"]);
            return;
        }
        // Check token ------------ end

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
