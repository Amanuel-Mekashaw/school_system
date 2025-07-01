<?php
require_once __DIR__ . '/../models/CommunicationRecord.php';
require_once __DIR__ . '/../models/CommunicationSubject.php';
require_once __DIR__ . '/../models/CommunicationTrait.php';
require_once __DIR__ . '/../config/database.php';

class CommunicationController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // POST /api/communication-records
    public function saveRecord($teacherId, $data) {
        // Validate required fields
        $required = ['student_id', 'date', 'semester', 'quarter', 'academic_year'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return $this->response(400, "Missing required field: $field");
            }
        }

        // Create main record
        $record = new CommunicationRecord($this->db);
        $record->teacher_id = $teacherId; // from JWT token
        $record->student_id = $data['student_id'];
        $record->date = $data['date'];
        $record->semester = (int)$data['semester'];
        $record->quarter = (int)$data['quarter'];
        $record->academic_year = $data['academic_year'];
        $record->need_extra_help_on = $data['need_extra_help_on'] ?? '';
        $record->secure_tuition_fee_for = $data['secure_tuition_fee_for'] ?? '';
        $record->letter_about = $data['letter_about'] ?? '';
        $record->teacher_note = $data['teacher_note'] ?? '';
        $record->parent_comment = $data['parent_comment'] ?? '';
        $record->parent_signed = !empty($data['parent_signed']);

        $recordId = $record->create();
        if (!$recordId) {
            return $this->response(500, "Failed to save communication record");
        }

        // Save subjects
        if (!empty($data['subjects']) && is_array($data['subjects'])) {
            foreach ($data['subjects'] as $subjectData) {
                if (empty($subjectData['subject_id'])) continue; // skip if no subject_id

                $cs = new CommunicationSubject($this->db);
                $cs->communication_record_id = $recordId;
                $cs->subject_id = $subjectData['subject_id'];
                $cs->homework = !empty($subjectData['homework']);
                $cs->worksheet = !empty($subjectData['worksheet']);
                $cs->test_exam = !empty($subjectData['test_exam']);
                $cs->save();
            }
        }

        // Save traits
        if (!empty($data['traits']) && is_array($data['traits'])) {
            foreach ($data['traits'] as $code => $value) {
                $ct = new CommunicationTrait($this->db);
                $ct->communication_record_id = $recordId;
                $ct->trait_code = $code;
                $ct->value = (bool)$value;
                $ct->save();
            }
        }

        return $this->response(201, "Communication record saved successfully", ['id' => $recordId]);
    }

    // GET /api/communication-records?teacher_id=&student_id=&date=&semester=&quarter=&academic_year=
    public function getRecords($teacherId, $filters) {
        // Enforce teacher_id from JWT for security
        $filters['teacher_id'] = $teacherId;

        $recordModel = new CommunicationRecord($this->db);
        $records = $recordModel->fetchByFilters($filters);

        // For each record, fetch subjects and traits
        foreach ($records as &$record) {
            $record['subjects'] = $this->getSubjects($record['id']);
            $record['traits'] = $this->getTraits($record['id']);
        }

        return $this->response(200, "Records fetched", $records);
    }

    private function getSubjects($recordId) {
        $sql = "SELECT s.id as subject_id, s.name as subject, cs.homework, cs.worksheet, cs.test_exam
                FROM communication_subjects cs
                JOIN subjects s ON cs.subject_id = s.id
                WHERE cs.communication_record_id = :recordId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':recordId', $recordId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getTraits($recordId) {
        $sql = "SELECT trait_code, value FROM communication_traits WHERE communication_record_id = :recordId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':recordId', $recordId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $traits = [];
        foreach ($rows as $row) {
            $traits[$row['trait_code']] = (bool)$row['value'];
        }
        return $traits;
    }

    private function response($code, $message, $data = null) {
        http_response_code($code);
        $response = ['status' => $code >= 400 ? 'error' : 'success', 'message' => $message];
        if ($data !== null) $response['data'] = $data;
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function updateRecord($data) {
    if (empty($data['id'])) {
        return $this->response(400, "Record ID required");
    }

    $sql = "UPDATE communication_records SET 
        need_extra_help_on = :need,
        secure_tuition_fee_for = :fee,
        letter_about = :letter,
        teacher_note = :note,
        parent_comment = :comment,
        parent_signed = :signed
        WHERE id = :id";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':need', $data['need_extra_help_on']);
    $stmt->bindParam(':fee', $data['secure_tuition_fee_for']);
    $stmt->bindParam(':letter', $data['letter_about']);
    $stmt->bindParam(':note', $data['teacher_note']);
    $stmt->bindParam(':comment', $data['parent_comment']);
    $stmt->bindParam(':signed', $data['parent_signed'], PDO::PARAM_BOOL);

    if ($stmt->execute()) {
        return $this->response(200, "Record updated");
    } else {
        return $this->response(500, "Update failed");
    }
}

public function deleteRecord($id) {
    if (!$id) return $this->response(400, "ID required");

    // delete sub-tables first
    $this->db->prepare("DELETE FROM communication_subjects WHERE communication_record_id = ?")->execute([$id]);
    $this->db->prepare("DELETE FROM communication_traits WHERE communication_record_id = ?")->execute([$id]);
    $stmt = $this->db->prepare("DELETE FROM communication_records WHERE id = ?");
    
    if ($stmt->execute([$id])) {
        return $this->response(200, "Record deleted");
    } else {
        return $this->response(500, "Deletion failed");
    }
}

public function getFilteredRecord($filters) {
    $required = ['student_id', 'date', 'semester', 'quarter', 'academic_year'];

    foreach ($required as $field) {
        if (empty($filters[$field])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "$field is required"]);
            return;
        }
    }

    $sql = "SELECT * FROM communication_records
            WHERE student_id = :student_id
              AND date = :date
              AND semester = :semester
              AND quarter = :quarter
              AND academic_year = :academic_year
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':student_id' => $filters['student_id'],
        ':date' => $filters['date'],
        ':semester' => $filters['semester'],
        ':quarter' => $filters['quarter'],
        ':academic_year' => $filters['academic_year']
    ]);

    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Record not found"]);
        return;
    }

    // Get subjects
    $stmt = $this->db->prepare("SELECT cs.*, s.name AS subject
                                FROM communication_subjects cs
                                JOIN subjects s ON s.id = cs.subject_id
                                WHERE cs.communication_record_id = ?");
    $stmt->execute([$record['id']]);
    $record['subjects'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get traits
    $stmt = $this->db->prepare("SELECT * FROM communication_traits WHERE communication_record_id = ?");
    $stmt->execute([$record['id']]);
    $traits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $traitMap = [];
    foreach ($traits as $trait) {
        $traitMap[$trait['trait_code']] = (bool) $trait['value'];
    }

    $record['traits'] = $traitMap;

    echo json_encode([
        "status" => "success",
        "record" => $record
    ]);
}


}
