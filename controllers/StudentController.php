<?php
require_once __DIR__ . '/../config/database.php';

class StudentController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM students");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getOne($id) {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO students (full_name, grade_id, section) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['full_name'], 
            $data['grade_id'], 
            $data['section']
        ]);
        echo json_encode(["status" => "success", "id" => $this->db->lastInsertId()]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE students SET full_name=?, grade_id=?, section=? WHERE id=?");
        $stmt->execute([
            $data['full_name'], 
            $data['grade_id'], 
            $data['section'], 
            $id
        ]);
        echo json_encode(["status" => "updated"]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM students WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(["status" => "deleted"]);
    }
}
