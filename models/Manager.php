<?php

require_once __DIR__ . '/../core/DBManager.php';

class Manager
{
    private $id;
    private $dbManager;

    public function __construct($id, DBManager $dbManager)
    {
        $this->id = $id;
        $this->dbManager = $dbManager;
    }

    public function getNumberOfEmployees()
    {
        $sql = "SELECT COUNT(*) FROM employees WHERE manager_id = ?";
        $stmt = $this->dbManager->query($sql, $this->id);
        return $stmt->fetchColumn();
    }

    public function getAllEmployees(): array
    {
        $sql = "SELECT * FROM employees WHERE manager_id = ?";
        return $this->dbManager->query($sql, $this->id)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmployeeById($employeeId)
    {
        $sql = "SELECT * FROM employees WHERE id = ?";
        return $this->dbManager->query($sql, $employeeId)->fetch(PDO::FETCH_ASSOC);
    }

    public function addEmployee($name, $email, $phone, $picture = null) {
        $picturePath = $picture;
        $sql = "INSERT INTO employees (name, email, phone, picture, manager_id) VALUES (?, ?, ?, ?, ?)";
        $this->dbManager->query($sql, $name, $email, $phone, $picturePath, $this->id);
    }
}
?>
