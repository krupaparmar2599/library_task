<?php

require_once './models/Member.php';

class MemberController
{
    private $memberModel;

    public function __construct($db)
    {
        $this->memberModel = new Member($db);
    }

    public function getAllMembers()
    {
        $members = $this->memberModel->getAll();
        http_response_code(200);
        echo json_encode($members);
    }

    public function getMember($id)
    {
        $member = $this->memberModel->getById($id);
        if ($member) {
            http_response_code(200);
            echo json_encode($member);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Member not found"]);
        }
    }

    public function createMember($data)
    {
        if (empty($data['name']) || empty($data['email']) || empty($data['mobile'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields"]);
            return;
        }

        $created = $this->memberModel->create($data);
        if ($created) {
            http_response_code(201);
            echo json_encode(["message" => "Member created successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to create member"]);
        }
    }

    public function updateMember($id, $data)
    {
        $updated = $this->memberModel->update($id, $data);
        if ($updated) {
            http_response_code(200);
            echo json_encode(["message" => "Member updated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to update member"]);
        }
    }

    public function deleteMember($id)
    {
        $deleted = $this->memberModel->delete($id);
        if ($deleted) {
            http_response_code(200);
            echo json_encode(["message" => "Member deleted successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete member"]);
        }
    }
}
