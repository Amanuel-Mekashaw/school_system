<?php
require_once __DIR__ . '/../config/database.php';

class TeacherController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT id, full_name, email FROM teachers");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getOne($id) {
        $stmt = $this->db->prepare("SELECT id, full_name, email FROM teachers WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO teachers (full_name, email, password_hash) VALUES (?, ?, ?)");

        // Password should be hashed before storing
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt->execute([
            $data['full_name'],
            $data['email'],
            $passwordHash
        ]);
        echo json_encode(["status" => "success", "id" => $this->db->lastInsertId()]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE teachers SET full_name = ?, email = ? WHERE id = ?");
        $stmt->execute([
            $data['full_name'],
            $data['email'],
            $id
        ]);
        echo json_encode(["status" => "updated"]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM teachers WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["status" => "deleted"]);
    }
}
