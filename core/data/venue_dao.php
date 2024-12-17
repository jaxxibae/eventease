<?php

require_once __DIR__ . '/../utils/mysql_pdo_connector.php';
require_once __DIR__ . '/../utils/encryption.php';

class VenueDAO {
    private $db;

    public function __construct() {
        $this->db = (new MySQLPDOConnector())->getConnection();
    }

    public function get_venues() {
        $stmt = $this->db->prepare('SELECT * FROM Venues');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create_venue($venue_name, $venue_location, $venue_capacity) {
        $stmt = $this->db->prepare('INSERT INTO Venues (name, location, capacity) VALUES (:name, :location, :capacity)');
        $stmt->bindParam(':name', $venue_name);
        $stmt->bindParam(':location', $venue_location);
        $stmt->bindParam(':capacity', $venue_capacity);
        $stmt->execute();
        return $this->get_venues();
    }
}