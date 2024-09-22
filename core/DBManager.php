<?php

class DBManager {

    private PDO $pdo;
    private $type;
    private $host;
    private $dbname;
    private $username;
    private $password;

    public function __construct() {
        $configPath = __DIR__ . "/../config/database.php";
        if (!file_exists($configPath)) {
            throw new Exception("Database configuration file does not exist: $configPath");
        }
    
        $configs = require $configPath;
    
        if (!is_array($configs)) {
            throw new Exception("Database configuration file is missing or invalid.");
        }
    
        $this->setParams($configs);
    }
    
    

    public function setParams(array $configs) {
        extract($configs);
        $this->type = $type ?? null;
        $this->host = $host ?? null;
        $this->dbname = $dbname ?? null;
        $this->username = $username ?? null;
        $this->password = $password ?? null;
    }

    public function getConnection(): PDO {
        if (empty($this->pdo)) {
            try {
                $this->pdo = new PDO($this->getDSN(), $this->username, $this->password);
            } catch (PDOException $e) {
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    public function query($sql, ...$args) {
        $pdo = $this->getConnection(); 
        $stmt = $pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    private function getDSN(): string {
        return sprintf("%s:host=%s;dbname=%s", $this->type ?? 'mysql', $this->host, $this->dbname);
    }
}
