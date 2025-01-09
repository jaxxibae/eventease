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

    public function get_venue_by_id($id) {
        $stmt = $this->db->prepare('SELECT * FROM Venues WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update_venue($id, $venue_name, $venue_location, $venue_capacity) {
        $stmt = $this->db->prepare('UPDATE Venues SET Name = :name, Location = :location, Capacity = :capacity WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $venue_name);
        $stmt->bindParam(':location', $venue_location);
        $stmt->bindParam(':capacity', $venue_capacity);
        $stmt->execute();
        return $this->get_venues();
    }

    public function delete_venue_by_id($id) {
        $stmt = $this->db->prepare('DELETE FROM Venues WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $this->get_venues();
    }
}