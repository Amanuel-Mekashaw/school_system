<?php
class CommunicationSubject {
    private $conn;
    private $table = "communication_subjects";

    public $id;
    public $communication_record_id;
    public $subject_id;
    public $homework;
    public $worksheet;
    public $test_exam;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function save() {
        $sql = "INSERT INTO {$this->table} (communication_record_id, subject_id, homework, worksheet, test_exam)
                VALUES (:record_id, :subject_id, :homework, :worksheet, :test_exam)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':record_id', $this->communication_record_id);
        $stmt->bindParam(':subject_id', $this->subject_id);
        $stmt->bindParam(':homework', $this->homework, PDO::PARAM_BOOL);
        $stmt->bindParam(':worksheet', $this->worksheet, PDO::PARAM_BOOL);
        $stmt->bindParam(':test_exam', $this->test_exam, PDO::PARAM_BOOL);

        return $stmt->execute();
    }
}
