<?php
require_once './models/Book.php';

class BookController
{
    private $bookModel;

    public function __construct($db)
    {
        $this->bookModel = new Book($db);
    }

    public function getAllBooks()
    {
        $books = $this->bookModel->getAll();
        http_response_code(200);
        echo json_encode($books);
    }

    public function getBook($id)
    {
        $book = $this->bookModel->getById($id);
        if ($book) {
            http_response_code(200);
            echo json_encode($book);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Book not found"]);
        }
    }

    public function createBook($data)
    {
        if (empty($data['title']) || empty($data['author']) || empty($data['genre']) || empty($data['published_year'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields"]);
            return;
        }

        $result = $this->bookModel->create($data);
        if ($result) {
            http_response_code(201);
            echo json_encode(["message" => "Book created successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to create book"]);
        }
    }

    public function updateBook($id, $data)
    {
        $book = $this->bookModel->getById($id);
        if (!$book) {
            http_response_code(404);
            echo json_encode(["error" => "Book not found"]);
            return;
        }

        $result = $this->bookModel->update($id, $data);
        if ($result) {
            http_response_code(200);
            echo json_encode(["message" => "Book updated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to update book"]);
        }
    }

    public function deleteBook($id)
    {
        $book = $this->bookModel->getById($id);
        if (!$book) {
            http_response_code(404);
            echo json_encode(["error" => "Book not found"]);
            return;
        }

        $result = $this->bookModel->softDelete($id);
        if ($result) {
            http_response_code(200);
            echo json_encode(["message" => "Book deleted "]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete book"]);
        }
    }
}
