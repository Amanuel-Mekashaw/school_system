<?php
require_once __DIR__ . '/../config/database.php';

class CommunicationRecord {
    private $conn;
    private $table = "communication_records";

    public $id;
    public $student_id;
    public $teacher_id;
    public $date;
    public $semester;
    public $quarter;
    public $academic_year;
    public $need_extra_help_on;
    public $secure_tuition_fee_for;
    public $letter_about;
    public $teacher_note;
    public $parent_comment;
    public $parent_signed;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Save new record, returns inserted ID or false
    public function create() {
        $sql = "INSERT INTO {$this->table} 
            (student_id, teacher_id, date, semester, quarter, academic_year, need_extra_help_on, secure_tuition_fee_for, letter_about, teacher_note, parent_comment, parent_signed) 
            VALUES 
            (:student_id, :teacher_id, :date, :semester, :quarter, :academic_year, :need_extra_help_on, :secure_tuition_fee_for, :letter_about, :teacher_note, :parent_comment, :parent_signed)";
        $stmt = $this->conn->prepare($sql);

        // sanitize
        $this->student_id = htmlspecialchars(strip_tags($this->student_id));
        $this->teacher_id = htmlspecialchars(strip_tags($this->teacher_id));
        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->semester = (int)$this->semester;
        $this->quarter = (int)$this->quarter;
        $this->academic_year = htmlspecialchars(strip_tags($this->academic_year));
        $this->need_extra_help_on = htmlspecialchars(strip_tags($this->need_extra_help_on));
        $this->secure_tuition_fee_for = htmlspecialchars(strip_tags($this->secure_tuition_fee_for));
        $this->letter_about = htmlspecialchars(strip_tags($this->letter_about));
        $this->teacher_note = htmlspecialchars(strip_tags($this->teacher_note));
        $this->parent_comment = htmlspecialchars(strip_tags($this->parent_comment));
        $this->parent_signed = (bool)$this->parent_signed;

        // bind parameters
        $stmt->bindParam(":student_id", $this->student_id);
        $stmt->bindParam(":teacher_id", $this->teacher_id);
        $stmt->bindParam(":date", $this->date);
        $stmt->bindParam(":semester", $this->semester);
        $stmt->bindParam(":quarter", $this->quarter);
        $stmt->bindParam(":academic_year", $this->academic_year);
        $stmt->bindParam(":need_extra_help_on", $this->need_extra_help_on);
        $stmt->bindParam(":secure_tuition_fee_for", $this->secure_tuition_fee_for);
        $stmt->bindParam(":letter_about", $this->letter_about);
        $stmt->bindParam(":teacher_note", $this->teacher_note);
        $stmt->bindParam(":parent_comment", $this->parent_comment);
        $stmt->bindParam(":parent_signed", $this->parent_signed, PDO::PARAM_BOOL);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Fetch records by filters (teacher, student, date, semester, quarter, academic_year)
    public function fetchByFilters($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['teacher_id'])) {
            $sql .= " AND teacher_id = :teacher_id";
            $params[':teacher_id'] = $filters['teacher_id'];
        }
        if (!empty($filters['student_id'])) {
            $sql .= " AND student_id = :student_id";
            $params[':student_id'] = $filters['student_id'];
        }
        if (!empty($filters['date'])) {
            $sql .= " AND date = :date";
            $params[':date'] = $filters['date'];
        }
        if (!empty($filters['semester'])) {
            $sql .= " AND semester = :semester";
            $params[':semester'] = $filters['semester'];
        }
        if (!empty($filters['quarter'])) {
            $sql .= " AND quarter = :quarter";
            $params[':quarter'] = $filters['quarter'];
        }
        if (!empty($filters['academic_year'])) {
            $sql .= " AND academic_year = :academic_year";
            $params[':academic_year'] = $filters['academic_year'];
        }

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
