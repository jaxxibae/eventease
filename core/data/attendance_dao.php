<?php

require_once __DIR__ . '/../utils/mysql_pdo_connector.php';

class AttendanceDAO {
    private $db;

    public function __construct() {
        $this->db = (new MySQLPDOConnector())->getConnection();
    }

    public function get_all_attendances(): array {
        $stmt = $this->db->prepare('SELECT Attendance.Id, e.Name as EventName, u.Name as UserName, Status FROM Attendance JOIN Events e ON Attendance.EventId = e.Id JOIN Users u ON Attendance.UserId = u.Id');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function register_attendance($event_id, $user_id): bool {
        $stmt = $this->db->prepare('INSERT INTO Attendance (EventId, UserId) VALUES (:eventId, :userId)');
        $stmt->bindParam(':eventId', $event_id);
        $stmt->bindParam(':userId', $user_id);
        return $stmt->execute();
    }

    public function get_attendance_by_event_id_and_user_id($event_id, $user_id): mixed {
        $stmt = $this->db->prepare('SELECT * FROM Attendance WHERE EventId = :eventId AND UserId = :userId');
        $stmt->bindParam(':eventId', $event_id);
        $stmt->bindParam(':userId', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_attendances_by_user_id($user_id): array {
        $stmt = $this->db->prepare('SELECT * FROM Attendance WHERE UserId = :userId AND Status = :status');
        $status = 'Confirmed';
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_attendance_by_event_id($event_id): array {
        $stmt = $this->db->prepare('SELECT Attendance.Id, e.Name as EventName, u.Name as UserName, u.EmailAddress AS UserEmail, u.Id AS UserId, Status, Motive FROM Attendance JOIN Events e ON Attendance.EventId = e.Id JOIN Users u ON Attendance.UserId = u.Id WHERE EventId = :eventId');
        $stmt->bindParam(':eventId', $event_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update_attendance($attendance_id, $status, $motive): bool {
        $stmt = $this->db->prepare('UPDATE Attendance SET Status = :status, Motive = :motive WHERE Id = :attendanceId');
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':motive', $motive);
        $stmt->bindParam(':attendanceId', $attendance_id);
        return $stmt->execute();
    }
}