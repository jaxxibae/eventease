<?php

require_once __DIR__ . '/../data/feedback_dao.php';

class FeedbackBusiness
{
    private $feedbackDAO;

    public function __construct()
    {
        $this->feedbackDAO = new FeedbackDAO();
    }

    public function get_all_feedbacks(): array
    {
        return $this->feedbackDAO->get_all_feedbacks();
    }

    public function create_feedback($event_id, $user_id, $rating, $comment): mixed
    {
        return $this->feedbackDAO->create_feedback($event_id, $user_id, $rating, $comment);
    }

    public function get_feedback_by_event_id_and_user_id($event_id, $user_id): mixed
    {
        return $this->feedbackDAO->get_feedback_by_event_id_and_user_id($event_id, $user_id);
    }
}