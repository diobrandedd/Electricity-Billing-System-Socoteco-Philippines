<?php
/**
 * Database Configuration for SOCOTECO II Billing Management System
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'socoteco_billing';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// Database connection instance
function getDB() {
    $database = new Database();
    return $database->getConnection();
}

// Helper function for prepared statements
function executeQuery($sql, $params = []) {
    $db = getDB();
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// Helper function to get last inserted ID from a specific statement
function getLastInsertIdFromStmt($stmt) {
    return $stmt->getConnection()->lastInsertId();
}

// Helper function to get single record
function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetch();
}

// Helper function to get multiple records
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll();
}

// Helper function to get last inserted ID
function getLastInsertId() {
    $db = getDB();
    return $db->lastInsertId();
}
?>
