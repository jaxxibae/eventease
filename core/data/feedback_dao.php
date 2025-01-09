<?php

require_once __DIR__ . '/../utils/mysql_pdo_connector.php';

class FeedbackDAO {
    private $db;

    public function __construct() {
        $this->db = (new MySQLPDOConnector())->getConnection();
    }

    public function get_all_feedbacks(): array {
        $stmt = $this->db->prepare('SELECT Feedback.Id, e.Name as EventName, u.Name as UserName, Rating FROM Feedback JOIN Events e ON Feedback.EventId = e.Id JOIN Users u ON Feedback.UserId = u.Id');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create_feedback($event_id, $user_id, $rating, $comment): bool {
        $stmt = $this->db->prepare('INSERT INTO Feedback (EventId, UserId, Rating, Comment) VALUES (:eventId, :userId, :rating, :comment)');
        $stmt->bindParam(':eventId', $event_id);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);
        return $stmt->execute();
    }

    public function get_feedback_by_event_id_and_user_id($event_id, $user_id): mixed {
        $stmt = $this->db->prepare('SELECT * FROM Feedback WHERE EventId = :eventId AND UserId = :userId');
        $stmt->bindParam(':eventId', $event_id);
        $stmt->bindParam(':userId', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_feedback_by_event_id($event_id): mixed {
        $stmt = $this->db->prepare('SELECT Feedback.*, u.Name as AttendeeName FROM Feedback JOIN Users u ON Feedback.UserId = u.Id WHERE EventId = :eventId');
        $stmt->bindParam(':eventId', $event_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_feedback_by_id($id): mixed {
        $stmt = $this->db->prepare('SELECT Feedback.*, u.Name AS AttendeeName, e.Name AS EventName FROM Feedback JOIN Users u ON Feedback.UserId = u.Id JOIN Events e ON Feedback.EventId = e.Id WHERE Feedback.Id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete_feedback_by_id($id): bool {
        $stmt = $this->db->prepare('DELETE FROM Feedback WHERE Id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}