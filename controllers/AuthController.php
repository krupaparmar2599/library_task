<?php
include_once './models/Auth.php';

class AuthController
{
    private $conn;
    private $authModel;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->authModel = new Auth($db);
    }

    public function login($data)
    {
        if (empty($data['email'])) {
            http_response_code(400);
            echo json_encode(["error" => "Email is required"]);
            return;
        }
        if (empty($data['password'])) {
            http_response_code(400);
            echo json_encode(["error" => "Password is required"]);
            return;
        }

        $user = $this->authModel->findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid email or password"]);
            return;
        }

        if (!empty($user['token'])) {
            echo json_encode(["error" => "User already logged in"]);
            return;
        }

        $token = bin2hex(random_bytes(32));
        $this->authModel->updateToken($user['id'], $token);

        echo json_encode([
            "message" => "Login successful",
            "token" => $token
        ]);
    }

    public function logout()
    {
        // Get Authorization header
        $headers = getallheaders();
        if (empty($headers['Authorization'])) {
            http_response_code(400);
            echo json_encode(["error" => "Token is required in Authorization header"]);
            return;
        }

        // Format check (Bearer <token>)
        $authHeader = $headers['Authorization'];
        if (strpos($authHeader, 'Bearer ') !== 0) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid token format"]);
            return;
        }

        // Extract token from header
        $token = substr($authHeader, 7);

        // Find user by token
        $user = $this->authModel->findByToken($token);
        if (!$user) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid token"]);
            return;
        }

        // Clear token (logout)
        $this->authModel->clearToken($user['id']);

        echo json_encode(["message" => "Logout successful"]);
    }
}
