<?php
class CommunicationTrait {
    private $conn;
    private $table = "communication_traits";

    public $id;
    public $communication_record_id;
    public $trait_code;
    public $value;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function save() {
        $sql = "INSERT INTO {$this->table} (communication_record_id, trait_code, value)
                VALUES (:record_id, :trait_code, :value)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':record_id', $this->communication_record_id);
        $stmt->bindParam(':trait_code', $this->trait_code);
        $stmt->bindParam(':value', $this->value, PDO::PARAM_BOOL);

        return $stmt->execute();
    }
}
