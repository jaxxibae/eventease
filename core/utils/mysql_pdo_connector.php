<?php
class MySQLPDOConnector
{
    private $host = '127.0.0.1';
    private $db = 'EventEase';
    private $user = 'root';
    private $pass = 'P@ssw0rd';
    private $charset = 'utf8mb4';
    private $pdo;
    private $error;

    public function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            throw new Exception("Database connection failed: " . $this->error);
        }
    }

    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            throw new Exception("No database connection established.");
        }
        return $this->pdo;
    }
}
