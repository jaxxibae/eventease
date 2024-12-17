<?php

require_once __DIR__ . '/../utils/mysql_pdo_connector.php';

class EventDAO {
    private $db;

    public function __construct() {
        $this->db = (new MySQLPDOConnector())->getConnection();
    }

    public function get_all_events(): array {
        $stmt = $this->db->prepare('SELECT e.*, v.Name as VenueName, v.Capacity as VenueCapacity, v.Location as VenueLocation, u.Name AS EventOrganizer FROM Events e JOIN Venues v ON e.VenueId = v.Id JOIN Users u ON e.CreatedById = u.Id');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_event_by_id($id): mixed {
        $stmt = $this->db->prepare('SELECT e.*, v.Name as VenueName, v.Capacity as VenueCapacity, v.Location as VenueLocation, u.Name AS EventOrganizer, u.Id AS EventOrganizerId FROM Events e JOIN Venues v ON e.VenueId = v.Id JOIN Users u ON e.CreatedById = u.Id WHERE e.Id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_ongoing_events_with_venues(): array {
        $stmt = $this->db->prepare('SELECT e.*, v.Name as VenueName, v.Capacity as VenueCapacity, v.Location as VenueLocation FROM Events e JOIN Venues v ON e.VenueId = v.Id WHERE e.EventDate >= :eventDate AND e.Active = true ORDER BY e.EventDate ASC');
        $eventDate = date('Y-m-d H:i:s');
        $stmt->bindParam(':eventDate', $eventDate);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_past_events_with_venues(): array {
        $stmt = $this->db->prepare('SELECT e.*, v.Name as VenueName, v.Capacity as VenueCapacity, v.Location as VenueLocation FROM Events e JOIN Venues v ON e.VenueId = v.Id WHERE e.EventDate < :eventDate AND e.Active = true ORDER BY e.EventDate DESC');
        $eventDate = date('Y-m-d H:i:s');
        $stmt->bindParam(':eventDate', $eventDate);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create_event($event_name, $event_description, $event_date, $event_time, $venue_id, $created_by_id): mixed {
        $stmt = $this->db->prepare('INSERT INTO Events (Name, Description, EventDate, EventTime, VenueId, CreatedById) VALUES (:event_name, :event_description, :event_date, :event_time, :venue_id, :created_by_id)');
        $stmt->bindParam(':event_name', $event_name);
        $stmt->bindParam(':event_description', $event_description);
        $stmt->bindParam(':event_date', $event_date);
        $stmt->bindParam(':event_time', $event_time);
        $stmt->bindParam(':venue_id', $venue_id);
        $stmt->bindParam(':created_by_id', $created_by_id);
        $stmt->execute();

        return $this->get_event_by_id($this->db->lastInsertId());
    }

    public function upload_event_picture($event_id, $uploaded_file_name, $picture_type): mixed {
        $stmt = $this->db->prepare("UPDATE Events SET $picture_type = :uploaded_file_name WHERE Id = :event_id");
        $stmt->bindParam(':uploaded_file_name', $uploaded_file_name);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();

        return $this->get_event_by_id($event_id);
    }

    public function get_events_by_created_by_id($user_id): array {
        $stmt = $this->db->prepare('SELECT e.*, v.Name as VenueName, v.Capacity as VenueCapacity, v.Location as VenueLocation FROM Events e JOIN Venues v ON e.VenueId = v.Id WHERE e.CreatedById = :user_id ORDER BY e.EventDate DESC');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_next_event_user_is_attending($user_id): mixed {
        $stmt = $this->db->prepare('SELECT e.*, v.Name as VenueName, v.Capacity as VenueCapacity, v.Location as VenueLocation, u.Name AS EventOrganizer FROM Events e JOIN Venues v ON e.VenueId = v.Id JOIN Users u ON e.CreatedById = u.Id WHERE e.Id = (SELECT EventId FROM Attendance WHERE UserId = :userId AND EventId = (SELECT MIN(EventId) FROM Attendance WHERE UserId = :userId))');
        $stmt->bindParam(':userId', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update_event($event_id, $event_name, $event_description, $event_date, $event_time, $venue_id): mixed {
        $stmt = $this->db->prepare('UPDATE Events SET Name = :event_name, Description = :event_description, EventDate = :event_date, EventTime = :event_time, VenueId = :venue_id WHERE Id = :event_id');
        $stmt->bindParam(':event_name', $event_name);
        $stmt->bindParam(':event_description', $event_description);
        $stmt->bindParam(':event_date', $event_date);
        $stmt->bindParam(':event_time', $event_time);
        $stmt->bindParam(':venue_id', $venue_id);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();

        return $this->get_event_by_id($event_id);
    }
}