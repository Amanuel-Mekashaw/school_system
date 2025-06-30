<?php
require_once __DIR__ . '/../config/database.php';

class StudentModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM students");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO students (full_name, grade_id, section) VALUES (?, ?, ?)");
        $stmt->execute([$data['full_name'], $data['grade_id'], $data['section']]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE students SET full_name=?, grade_id=?, section=? WHERE id=?");
        return $stmt->execute([$data['full_name'], $data['grade_id'], $data['section'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM students WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
