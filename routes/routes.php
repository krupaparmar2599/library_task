<?php

require_once './config/database.php';
$db = new Database();
$conn = $db->connect();

require_once './controllers/BookController.php';
$bookController = new BookController($conn);

require_once './controllers/MemberController.php';
$memberController = new MemberController($conn);

require_once './controllers/ManagementController.php';
$managementController = new ManagementController($conn);

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];
$segments = explode('/', $uri);

// Adjust for local folder bcz having multi local folders
if ($segments[0] === 'Projects' || $segments[0] === 'library_task') {
    array_shift($segments);
    if ($segments[0] === 'library_task') array_shift($segments);
}

$resource = $segments[0] ?? null;
$id = $segments[1] ?? null;

// Books CRUD  ---------------------------------------
if ($resource === 'books') {
    if ($method === 'GET' && !$id) $bookController->getAllBooks();
    if ($method === 'GET' && $id) $bookController->getBook($id);
    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $bookController->createBook($data);
    }
    if ($method === 'PUT' && $id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $bookController->updateBook($id, $data);
    }
    if ($method === 'DELETE' && $id) $bookController->deleteBook($id);
}

// Members CRUD ---------------------------------------
else if ($resource === 'members') {
    if ($method === 'GET' && !$id) $memberController->getAllMembers();
    if ($method === 'GET' && $id) $memberController->getMember($id);
    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $memberController->createMember($data);
    }
    if ($method === 'PUT' && $id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $memberController->updateMember($id, $data);
    }
    if ($method === 'DELETE' && $id) $memberController->deleteMember($id);
}

// Management CRUD -----------------------------
else if ($resource === 'management') {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $managementController->issueBook($data);
    }
    if ($method === 'PUT') {
        $data = json_decode(file_get_contents("php://input"), true);
        $managementController->returnBook($data);
    }
    $subResource = $segments[1] ?? null;
    if ($method === 'GET') {
        if ($subResource === 'issued-books') {
            $managementController->getCurrentlyIssuedBooks();
        } else {
            $managementController->getOverdueBooks();
        }
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Invalid resource"]);
}
