<?php
require_once __DIR__ . '/../config/database.php';

class SubjectController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM subjects");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getOne($id) {
        $stmt = $this->db->prepare("SELECT * FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO subjects (name) VALUES (?)");
        $stmt->execute([
            $data['name']
        ]);
        echo json_encode(["status" => "success", "id" => $this->db->lastInsertId()]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE subjects SET name = ? WHERE id = ?");
        $stmt->execute([
            $data['name'],
            $id
        ]);
        echo json_encode(["status" => "updated"]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["status" => "deleted"]);
    }
}
